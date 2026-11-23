<?php

namespace App\Helpers;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

trait GmailSenderHelper
{
    protected static function client(): \Google_Client
    {
        $client = new Google_Client();
        $client->setApplicationName('Laravel Gmail Sender');
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
        $client->setScopes([Google_Service_Gmail::GMAIL_SEND]);

        // استخدم التوكن المحفوظ من خطوات التفويض
        $tokenPath = storage_path('app/google/token.json');
        if (!file_exists($tokenPath)) {
            throw new \RuntimeException('Google token not found. Authorize at /google/auth first.');
        }
        $client->setAccessToken(json_decode(file_get_contents($tokenPath), true));
        if ($client->isAccessTokenExpired() && $client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    /**
     * $to:        string|array 
     * $subject:   string
     * $htmlBody:  string (HTML)
     * $attachments: [ ['path'=>absPath,'name'=>'file.pdf','mime'=>'application/pdf'], ... ]
     */
    public static function sendMail($to, string $subject, string $htmlBody, array $attachments = []): void
    {
        $client  = self::client();
        $service = new \Google_Service_Gmail($client);

        $fromEmail = env('MAIL_FROM_ADDRESS', 'no-reply@noontraining.com');
        $fromName  = env('MAIL_FROM_NAME', 'Noon Training');

        // Get all valid emails
        $allEmails = self::getValidEmails($to);

        // Gmail API limit is 100 recipients per message, but we'll use 50 to be safe
        $batchSize = 50;
        $batches = array_chunk($allEmails, $batchSize);

        \Log::info("Sending email to " . count($allEmails) . " recipients in " . count($batches) . " batches");

        foreach ($batches as $batchIndex => $emailBatch) {
            \Log::info("Sending batch " . ($batchIndex + 1) . " with " . count($emailBatch) . " recipients");

            $toList = implode(', ', $emailBatch);

            // Send email to this batch
            self::sendSingleBatch($service, $toList, $subject, $htmlBody, $attachments, $fromEmail, $fromName);

            // Add small delay between batches to avoid rate limiting
            if ($batchIndex < count($batches) - 1) {
                sleep(1);
            }
        }
    }

    private static function sendSingleBatch($service, string $toList, string $subject, string $htmlBody, array $attachments, string $fromEmail, string $fromName): void
    {
        // Helpers
        $b64 = fn(string $s) => chunk_split(base64_encode($s));
        $encHeader = fn(string $s) => '=?UTF-8?B?' . base64_encode($s) . '?=';

        // Plain-text fallback
        $plain = trim(
            preg_replace(
                "/[ \t]+/",
                ' ',
                html_entity_decode(strip_tags($htmlBody), ENT_QUOTES | ENT_HTML5, 'UTF-8')
            )
        );

        $boundaryMixed = 'mix_' . bin2hex(random_bytes(8));
        $boundaryAlt   = 'alt_' . bin2hex(random_bytes(8));
        $dateHeader    = date('r');

        // Headers must be ASCII: encode a non-ASCII (Arabic) display name as RFC 2047,
        // otherwise mail clients show it as mojibake (e.g. "Ù…Ø¹Ù‡Ø¯...")
        $fromNameHeader = preg_match('/[^\x20-\x7E]/', $fromName)
            ? $encHeader($fromName)
            : '"' . addcslashes($fromName, '"\\') . '"';
        $fromHeader = "{$fromNameHeader} <{$fromEmail}>";

        $headers = [
            "From: {$fromHeader}",
            "To: {$toList}",
            "Subject: " . $encHeader($subject),
            "MIME-Version: 1.0",
            "Date: {$dateHeader}",
        ];

        if (count($attachments)) {
            // multipart/mixed with inner multipart/alternative
            $headers[] = "Content-Type: multipart/mixed; boundary=\"{$boundaryMixed}\"";

            $body  = "--{$boundaryMixed}\r\n";
            $body .= "Content-Type: multipart/alternative; boundary=\"{$boundaryAlt}\"\r\n\r\n";

            // text/plain part
            $body .= "--{$boundaryAlt}\r\n";
            $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $b64($plain) . "\r\n";

            // text/html part
            $body .= "--{$boundaryAlt}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $b64($htmlBody) . "\r\n";
            $body .= "--{$boundaryAlt}--\r\n";

            // attachments
            foreach ($attachments as $att) {
                $fileData = file_get_contents($att['path']);
                $mime     = $att['mime'] ?? 'application/octet-stream';
                $name     = $att['name'] ?? basename($att['path']);

                $body .= "\r\n--{$boundaryMixed}\r\n";
                $body .= "Content-Type: {$mime}; name=\"{$name}\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"{$name}\"\r\n\r\n";
                $body .= $b64($fileData);
            }
            $body .= "\r\n--{$boundaryMixed}--";
            $rawMessageString = implode("\r\n", $headers) . "\r\n\r\n" . $body;
        } else {
            // multipart/alternative only
            $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundaryAlt}\"";

            $body  = "--{$boundaryAlt}\r\n";
            $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $b64($plain) . "\r\n";

            $body .= "--{$boundaryAlt}\r\n";
            $body .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= $b64($htmlBody) . "\r\n";
            $body .= "--{$boundaryAlt}--";

            $rawMessageString = implode("\r\n", $headers) . "\r\n\r\n" . $body;
        }

        // Base64 URL-safe
        $raw = rtrim(strtr(base64_encode($rawMessageString), '+/', '-_'), '=');
        $message = new \Google_Service_Gmail_Message();
        $message->setRaw($raw);

        $service->users_messages->send('me', $message);
    }


    /**
     * Get valid emails from input
     */
    private static function getValidEmails($to): array
    {
        if (is_string($to)) {
            $emails = [$to];
        } elseif (is_array($to)) {
            $emails = $to;
        } else {
            throw new \InvalidArgumentException('To parameter must be string or array');
        }

        $validEmails = [];
        foreach ($emails as $email) {
            $email = trim($email);

            // Auto-fix common email typos
            $email = self::fixCommonEmailTypos($email);

            // More strict email validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
                \Log::warning("Skipping invalid email address: {$email}");
                continue;
            }

            // Ensure no special characters that could break headers
            if (preg_match('/[\r\n\t<>"]/', $email)) {
                \Log::warning("Email contains invalid characters: {$email}");
                continue;
            }

            // Remove any potential encoding issues
            $email = mb_convert_encoding($email, 'ASCII', 'UTF-8');

            $validEmails[] = $email;
        }

        if (empty($validEmails)) {
            throw new \InvalidArgumentException('No valid email addresses found');
        }

        return array_unique($validEmails);
    }

    // /**
    //  * Format and validate email addresses
    //  */
    // private static function formatEmailAddresses($to): string
    // {
    //     if (is_string($to)) {
    //         $emails = [$to];
    //     } elseif (is_array($to)) {
    //         $emails = $to;
    //     } else {
    //         throw new \InvalidArgumentException('To parameter must be string or array');
    //     }

    //     $validEmails = [];
    //     foreach ($emails as $email) {
    //         $email = trim($email);

    //         // Auto-fix common email typos
    //         $email = self::fixCommonEmailTypos($email);

    //         // More strict email validation
    //         if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    //             \Log::warning("Skipping invalid email address: {$email}");
    //             continue;
    //         }

    //         // Ensure no special characters that could break headers
    //         if (preg_match('/[\r\n\t<>"]/', $email)) {
    //             \Log::warning("Email contains invalid characters: {$email}");
    //             continue;
    //         }

    //         // Remove any potential encoding issues
    //         $email = mb_convert_encoding($email, 'ASCII', 'UTF-8');

    //         $validEmails[] = $email;
    //     }

    //     if (empty($validEmails)) {
    //         throw new \InvalidArgumentException('No valid email addresses found');
    //     }

    //     // Ensure no commas in individual email addresses and proper spacing
    //     return implode(', ', array_unique($validEmails));
    // }

    /**
     * Fix common email typos
     */
    private static function fixCommonEmailTypos(string $email): string
    {
        $fixes = [
            'gmailcom' => 'gmail.com',
            'gmail.co' => 'gmail.com',
            'gmai.com' => 'gmail.com',
            'yahoocom' => 'yahoo.com',
            'yahoo.co' => 'yahoo.com',
            'hotmailcom' => 'hotmail.com',
            'hotmail.co' => 'hotmail.com',
            'outlookcom' => 'outlook.com',
            'outlook.co' => 'outlook.com',
        ];

        foreach ($fixes as $wrong => $correct) {
            if (str_ends_with($email, '@' . $wrong)) {
                $email = str_replace('@' . $wrong, '@' . $correct, $email);
                \Log::info("Auto-corrected email: {$email} (was: @{$wrong})");
                break;
            }
        }

        return $email;
    }
}

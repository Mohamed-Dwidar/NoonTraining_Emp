<?php
return [
    'credentials_path' => storage_path('app/google/credentials.json'),
    'token_path'       => storage_path('app/google/token.json'),
    'scopes'           => [Google\Service\Gmail::GMAIL_SEND],
    'user_to_impersonate' => 'youraccount@gmail.com', // the Gmail account to send from
];

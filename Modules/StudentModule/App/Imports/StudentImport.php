<?php

namespace Modules\StudentModule\App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Modules\StudentModule\Services\StudentService;

class StudentImport implements ToCollection, WithEvents {
    protected $studentService;
    protected int $employeeId;
    protected ?int $commissionId = null;

    public function __construct(StudentService $studentService, int $employeeId) {
        $this->studentService = $studentService;
        $this->employeeId     = $employeeId;
    }

    public function registerEvents(): array {
        return [
            // Each sub-sheet is named "{commission id} - {name}" (see StudentsTemplateExport).
            // Capture the id here so every row imported from that sheet is tagged with it.
            BeforeSheet::class => function (BeforeSheet $event) {
                $title = $event->sheet->getDelegate()->getTitle();
                $this->commissionId = preg_match('/^(\d+)/', trim($title), $m) ? (int) $m[1] : null;
            },
        ];
    }

    private function parseDate(mixed $value): ?string {
        if (empty($value)) return null;

        // Excel serial number (e.g. 46178)
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                ->format('Y-m-d');
        }

        // Already a formatted string (e.g. "05/06/2026" or "2026-06-05")
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }

    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue;
            }

            if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3])) {
                continue;
            }

            $data = [
                'employee_id'         => $this->employeeId,
                'month'               => date('Y-m'),
                'name'                => $row[0],
                'mobile'              => $row[1],
                'national_id'         => $row[2],
                'course_name'         => $row[3],
                'total_amount'        => $row[4] ?? 0,
                'paid_amount'         => $row[5] ?? 0,
                'payment_method'      => $row[6] ?? null,
                'payment_date'        => $this->parseDate($row[7] ?? null),
                'previous_student_of' => $row[8] ?? null,
                'commission_id'       => $this->commissionId,
            ];

            $this->studentService->create($data);
        }
    }
}

<?php

namespace Modules\StudentModule\App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\StudentModule\Services\StudentService;

class StudentImport implements ToCollection {
    protected $studentService;
    protected int $employeeId;

    public function __construct(StudentService $studentService, int $employeeId) {
        $this->studentService = $studentService;
        $this->employeeId     = $employeeId;
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

            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                continue;
            }

            $data = [
                'employee_id'         => $this->employeeId,
                'month'               => date('Y-m'),
                'name'                => $row[0],
                'mobile'              => $row[1],
                'course_name'         => $row[2],
                'total_amount'        => $row[3] ?? 0,
                'paid_amount'         => $row[4] ?? 0,
                'payment_method'      => $row[5] ?? null,
                'payment_date'        => $this->parseDate($row[6] ?? null),
                'previous_student_of' => $row[7] ?? null,
            ];

            $this->studentService->create($data);
        }
    }
}

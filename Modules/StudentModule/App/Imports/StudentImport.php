<?php

namespace Modules\StudentModule\App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Modules\StudentModule\Services\StudentService;

class StudentImport implements ToCollection, WithEvents {
    protected $studentService;
    protected int $employeeId;
    protected string $month;
    protected ?int $commissionId = null;
    protected string $sheetTitle = '';
    protected array $rows = [];

    // Valid sample values shown next to each import error
    private const EXAMPLES = [
        'name'                => 'محمد أحمد',
        'mobile'              => '551234567',
        'national_id'         => '1012345678',
        'course_name'         => 'دورة اللغة الإنجليزية',
        'total_amount'        => '1500',
        'paid_amount'         => '500',
        'payment_method'      => 'كاش / حوالة / تمارا / تابي',
        'payment_date'        => '02/08/2026',
        'previous_student_of' => 'أحمد علي',
    ];

    public function __construct(StudentService $studentService, int $employeeId, string $month) {
        $this->studentService = $studentService;
        $this->employeeId     = $employeeId;
        $this->month          = $month;
    }

    public function registerEvents(): array {
        return [
            // Each sub-sheet is named "{commission id} - {name}" (see StudentsTemplateExport).
            // Capture the id here so every row imported from that sheet is tagged with it.
            BeforeSheet::class => function (BeforeSheet $event) {
                $title = $event->sheet->getDelegate()->getTitle();
                $this->sheetTitle   = $title;
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

    private function cell(mixed $value): ?string {
        $value = trim((string) ($value ?? ''));
        return $value === '' ? null : $value;
    }

    /**
     * Only collects rows — nothing is saved here.
     * Call errors() after Excel::import(), then save() if there are none.
     */
    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue;
            }

            $cells = array_map(fn($v) => $this->cell($v), array_slice($row->toArray(), 0, 9));
            if (empty(array_filter($cells, fn($v) => $v !== null))) {
                continue; // fully empty row
            }

            $rawDate = $cells[7] ?? null;

            $this->rows[] = [
                'sheet' => $this->sheetTitle,
                'row'   => $key + 1, // Excel row number (row 1 is the header)
                'data'  => [
                    'employee_id'         => $this->employeeId,
                    'month'               => $this->month,
                    'name'                => $cells[0] ?? null,
                    'mobile'              => $cells[1] ?? null,
                    'national_id'         => $cells[2] ?? null,
                    'course_name'         => $cells[3] ?? null,
                    'total_amount'        => $cells[4] ?? 0,
                    'paid_amount'         => $cells[5] ?? 0,
                    'payment_method'      => $cells[6] ?? null,
                    // Keep the raw text when it can't be parsed so validation reports it as invalid
                    'payment_date'        => $this->parseDate($rawDate) ?? $rawDate,
                    'previous_student_of' => $cells[8] ?? null,
                    'commission_id'       => $this->commissionId,
                ],
            ];
        }
    }

    /**
     * Validate every collected row. Returns error messages pointing to sheet + row; empty when the file is valid.
     */
    public function errors(int $limit = 50): array {
        if (empty($this->rows)) {
            return ['الملف لا يحتوي على أي بيانات طلاب'];
        }

        $errors = [];
        foreach ($this->rows as $item) {
            $validator = Validator::make($item['data'], [
                'name'                => 'required|string|max:255',
                'mobile'              => 'required|string|max:20',
                'national_id'         => 'required|string|max:20',
                'course_name'         => 'required|string|max:255',
                'total_amount'        => 'integer|min:0',
                'paid_amount'         => 'integer|min:0',
                'payment_method'      => 'required|string|max:50',
                'payment_date'        => 'required|date_format:Y-m-d',
                'previous_student_of' => 'nullable|string|max:255',
            ], [
                'name.required'           => 'اسم الطالب مطلوب',
                'name.max'                => 'اسم الطالب أطول من 255 حرف',
                'mobile.required'         => 'رقم الجوال مطلوب',
                'mobile.max'              => 'رقم الجوال أطول من 20 رقم',
                'national_id.required'    => 'رقم الهوية مطلوب',
                'national_id.max'         => 'رقم الهوية أطول من 20 رقم',
                'course_name.required'    => 'اسم الدورة مطلوب',
                'course_name.max'         => 'اسم الدورة أطول من 255 حرف',
                'total_amount.integer'    => 'المبلغ كامل يجب أن يكون رقماً صحيحاً',
                'total_amount.min'        => 'المبلغ كامل لا يمكن أن يكون سالباً',
                'paid_amount.integer'     => 'المدفوع يجب أن يكون رقماً صحيحاً',
                'paid_amount.min'         => 'المدفوع لا يمكن أن يكون سالباً',
                'payment_method.required' => 'طريقة الدفع مطلوبة',
                'payment_method.max'      => 'طريقة الدفع أطول من 50 حرف',
                'payment_date.required'   => 'تاريخ الدفع مطلوب',
                'payment_date.date_format' => 'تاريخ الدفع غير صالح',
                'previous_student_of.max' => 'حقل "الطالب سابقاً تبع مين" أطول من 255 حرف',
            ]);

            if ($validator->fails()) {
                // One message per field, followed by an example of a valid value
                $messages = [];
                foreach ($validator->errors()->messages() as $field => $fieldMessages) {
                    $messages[] = $fieldMessages[0] . ' (مثال: ' . self::EXAMPLES[$field] . ')';
                }

                $errors[] = 'الورقة «' . $item['sheet'] . '» - الصف ' . $item['row'] . ': '
                    . implode('، ', $messages);
            }
        }

        if (count($errors) > $limit) {
            $more   = count($errors) - $limit;
            $errors = array_slice($errors, 0, $limit);
            $errors[] = 'و ' . $more . ' صفوف أخرى بها أخطاء';
        }

        return $errors;
    }

    /**
     * Save all collected rows in one transaction. Call only when errors() is empty.
     */
    public function save(): int {
        DB::transaction(function () {
            foreach ($this->rows as $item) {
                $this->studentService->create($item['data']);
            }
        });

        return count($this->rows);
    }
}

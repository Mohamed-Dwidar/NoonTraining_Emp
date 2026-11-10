<?php

namespace Modules\StudentModule\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Students Excel export: one sheet per commission (students.commission_id), named after the commission,
 * ordered by commission id. Students without a (still existing) commission go to «بدون عمولة» (last).
 *
 * $commissions = PayrollService::studentsCommissionAmounts($students): [student_id => ['name', 'amount', …]].
 */
class StudentsExport implements WithMultipleSheets
{
    use UniqueSheetTitles;

    private const NO_COMMISSION = 'بدون عمولة';

    private $students;
    private array $commissions;

    function __construct($students, array $commissions = [])
    {
        $this->students    = collect($students);
        $this->commissions = $commissions;
    }

    public function sheets(): array
    {
        $groups = $this->students
            ->groupBy(fn($s) => isset($this->commissions[$s->id]) ? (int) $s->commission_id : 0)
            ->sortKeys();

        // «بدون عمولة» (key 0) goes last
        if ($groups->has(0)) {
            $groups = $groups->except(0)->put(0, $groups->get(0));
        }

        $sheets = [];
        $used   = [];
        foreach ($groups as $commissionId => $students) {
            $name   = $commissionId ? $this->commissions[$students->first()->id]['name'] : self::NO_COMMISSION;
            $title  = $this->uniqueTitle($name, $used);
            $used[] = $title;
            $sheets[] = new StudentsSheetExport($title, $students, $this->commissions);
        }

        // Nothing to export → one empty sheet with the headers
        if (empty($sheets)) {
            $sheets[] = new StudentsSheetExport('الطلاب', collect(), []);
        }

        return $sheets;
    }
}

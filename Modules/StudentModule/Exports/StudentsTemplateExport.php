<?php

namespace Modules\StudentModule\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\CommissionModule\App\Http\Models\Commission;

class StudentsTemplateExport implements WithMultipleSheets
{
    use UniqueSheetTitles;

    public function sheets(): array
    {
        $commissions = Commission::get(['id', 'name']);

        $sheets = [];
        $usedTitles = [];

        foreach ($commissions as $commission) {
            $title = $this->uniqueTitle($commission->id . ' - ' . $commission->name, $usedTitles);
            $usedTitles[] = $title;
            $sheets[] = new StudentsTemplateSheetExport($title);
        }

        if (empty($sheets)) {
            $sheets[] = new StudentsTemplateSheetExport('الطلاب');
        }

        return $sheets;
    }
}

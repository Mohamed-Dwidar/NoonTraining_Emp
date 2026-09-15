<?php

namespace Modules\StudentModule\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\CommissionModule\App\Http\Models\Commission;

class StudentsTemplateExport implements WithMultipleSheets
{
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

    private function uniqueTitle(string $title, array $usedTitles): string
    {
        // Excel sheet names: max 31 chars, no : \ / ? * [ ]
        $title = preg_replace('/[:\\\\\/\?\*\[\]]/', '-', $title);
        $title = mb_substr($title, 0, 31);

        $base = $title;
        $i = 2;
        while (in_array($title, $usedTitles, true)) {
            $suffix = ' (' . $i . ')';
            $title = mb_substr($base, 0, 31 - mb_strlen($suffix)) . $suffix;
            $i++;
        }

        return $title;
    }
}

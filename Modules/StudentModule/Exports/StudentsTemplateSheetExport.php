<?php

namespace Modules\StudentModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsTemplateSheetExport implements FromView, WithTitle, WithEvents
{
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function view(): View
    {
        return view('studentmodule::Admin.exports.students_template_sheet');
    }

    public function title(): string
    {
        return $this->title;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
}

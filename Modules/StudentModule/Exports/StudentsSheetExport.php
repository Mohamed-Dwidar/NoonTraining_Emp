<?php

namespace Modules\StudentModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

/** One sheet of the students export (the students of one commission). */
class StudentsSheetExport implements FromView, WithTitle, WithEvents
{
    private string $title;
    private $students;
    private array $commissions;

    public function __construct(string $title, $students, array $commissions = [])
    {
        $this->title       = $title;
        $this->students    = $students;
        $this->commissions = $commissions;
    }

    public function view(): View
    {
        $students    = $this->students;
        $commissions = $this->commissions;
        return view('studentmodule::Admin.exports.students_export', compact('students', 'commissions'));
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

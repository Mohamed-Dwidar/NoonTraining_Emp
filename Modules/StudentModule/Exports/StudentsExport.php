<?php

namespace Modules\StudentModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsExport implements FromView, WithEvents
{
    private $students;

    function __construct($students)
    {
        $this->students = $students;
    }

    public function view(): View
    {
        $students = $this->students;
        return view('studentmodule::Admin.exports.students_export',  compact('students'));
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

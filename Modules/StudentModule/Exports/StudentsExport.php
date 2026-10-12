<?php

namespace Modules\StudentModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsExport implements FromView, WithEvents
{
    private $students;
    private $commissions;

    function __construct($students, array $commissions = [])
    {
        $this->students    = $students;
        $this->commissions = $commissions;
    }

    public function view(): View
    {
        $students    = $this->students;
        $commissions = $this->commissions;
        return view('studentmodule::Admin.exports.students_export',  compact('students', 'commissions'));
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

<?php

namespace Modules\ReportModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CourseExport implements FromView, WithEvents
{
    private $courses;

    function __construct($courses)
    {
        $this->courses = $courses;
    }

    public function view(): View
    {
        $courses = $this->courses;
        return view('reportmodule::admin.exports.courses_export',  compact('courses'));
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

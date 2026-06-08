<?php

namespace Modules\ReportModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CourseRegExport implements FromView, WithEvents
{
    private $courses_regs;
    private $report_type;

    function __construct($courses_regs, $report_type = '')
    {
        $this->courses_regs = $courses_regs;
        $this->report_type = $report_type;
    }

    public function view(): View
    {
        $courses_regs = $this->courses_regs;
        return view('reportmodule::admin.exports.course_reg_export',  compact('courses_regs'));
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

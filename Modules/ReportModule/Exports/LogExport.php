<?php

namespace Modules\ReportModule\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LogExport implements FromView, WithEvents
{
    private $logs;
    private $report_type;

    function __construct($logs, $report_type = '')
    {
        $this->logs = $logs;
        $this->report_type = $report_type;
    }

    public function view(): View
    {
        $logs = $this->logs;
        return view('reportmodule::admin.exports.log_export',  compact('logs'));
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

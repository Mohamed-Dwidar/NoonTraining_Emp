<?php

namespace Modules\AttendanceModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Attendance extends Model {
    use HasFactory;

    protected $guarded = [];

    const STATUS_REGULAR  = 'منتظم';
    const STATUS_REPEATED = 'غياب متكرر';
    const ABSENCE_THRESHOLD = 3; // days_absent >= this → غياب متكرر

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public static function computeStatus(int $daysAbsent): string {
        return $daysAbsent >= self::ABSENCE_THRESHOLD ? self::STATUS_REPEATED : self::STATUS_REGULAR;
    }

    public static function computeRate(int $daysPresent, int $monthlyWorkingDays): float {
        if ($monthlyWorkingDays <= 0) return 0;
        return round(($daysPresent / $monthlyWorkingDays) * 100, 2);
    }
}

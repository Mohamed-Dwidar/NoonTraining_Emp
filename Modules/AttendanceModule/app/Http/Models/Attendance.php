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

     public function scopeFilter($query, $request = []){
        if (!empty($request['month'])) {
            $query->where('month', $request['month']);
        }

        if (!empty($request['branch_id'])) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('branch_id', $request['branch_id']);
            });
        }

        if (!empty($request['department_id'])) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request['department_id']);
            });
        }

        return $query->with('employee');
    }
}

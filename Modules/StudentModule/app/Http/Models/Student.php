<?php

namespace Modules\StudentModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Student extends Model {
    use HasFactory;

    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function remainingAmount(): int {
        return (int) $this->total_amount - (int) $this->paid_amount;
    }

    public function scopeFilter($query, $request = []) {
        if (isset($request['branch_id']) && $request['branch_id']) {
            $query->whereHas('employee', fn($q) => $q->where('branch_id', $request['branch_id']));
        }
        if (isset($request['department_id']) && $request['department_id']) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $request['department_id']));
        }
        if (isset($request['employee_id']) && $request['employee_id']) {
            $query->where('employee_id', $request['employee_id']);
        }
        if (!empty($request['search'])) {
            $search = $request['search'];
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('course_name', 'like', "%{$search}%"));
        }
        if (!empty($request['month'])) {
            $query->where('month', $request['month']);
        }

        return $query;
    }
}

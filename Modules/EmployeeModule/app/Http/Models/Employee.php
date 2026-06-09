<?php

namespace Modules\EmployeeModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\BranchModule\App\Http\Models\Branch;
use Modules\DepartmentModule\App\Http\Models\Department;
use Modules\UserModule\App\Http\Models\User;

class Employee extends Model {
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->morphMany(User::class, 'userable');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getTotalWorkingHoursAttribute() {
        return $this->monthly_working_days * $this->daily_working_hours;
    }

    public function getDailySalaryAttribute() {
        return $this->basic_salary / $this->monthly_working_days;
    }

    public function getHourlySalaryAttribute() {
        return $this->daily_salary / $this->daily_working_hours;
    }

    public function scopeFilter($query, $request = []) {
        if (!empty($request['search'])) {
            $term = $request['search'];
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('job', 'like', '%' . $term . '%')
                    ->orWhereHas('user', function ($q2) use ($term) {
                        $q2->where('email', 'like', '%' . $term . '%');
                    });
            });
        }
        if (!empty($request['branch_id'])) {
            $query->where('branch_id', $request['branch_id']);
        }
        if (!empty($request['department_id'])) {
            $query->where('department_id', $request['department_id']);
        }
        if (!empty($request['status'])) {
            $status = is_array($request['status']) ? $request['status'] : [$request['status']];
            $query->whereIn('status', $status);
        }
        return $query;
    }
}

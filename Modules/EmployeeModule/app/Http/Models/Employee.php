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
        return $this->basic_salary / $this->total_working_hours;
    }

     public function getHourlySalaryAttribute() {
        return $this->daily_salary / $this->daily_working_hours;
    }

    public function scopeFilter($query, $request = []) {
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }
        if (isset($request['branch_id'])) {
            $query->where('branch_id', $request['branch_id']);
        }
        if (isset($request['department_id'])) {
            $query->where('department_id', $request['department_id']);
        }
        return $query;
    }
}

<?php

namespace Modules\PayrollModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Payroll extends Model {
    use HasFactory;

    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
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

<?php

namespace Modules\LeaveModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Leave extends Model {
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    const TYPES = [
        'إجازة سنوية',
        'إجازة مرضية',
        'إجازة عارضة',
        'إجازة بدون راتب',
        'إجازة أمومة',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeFilter($query, $request) {
        if ($request->filled('month')) {
            $query->where('month', $request->input('month'));
        }

        if ($request->filled('branch_id')) {
            $query->whereHas('employee', fn($q) => $q->where('branch_id', $request->input('branch_id')));
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $request->input('department_id')));
        }

        return $query;
    }
}

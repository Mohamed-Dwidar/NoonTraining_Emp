<?php

namespace Modules\TaskModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Task extends Model {
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    const STATUSES = [
        'new'         => 'جديدة',
        'in_progress' => 'قيد التنفيذ',
        'completed'   => 'مكتملة',
        'late'        => 'متأخرة',
    ];

    const STATUS_COLORS = [
        'new'         => 'secondary',
        'in_progress' => 'warning',
        'completed'   => 'success',
        'late'        => 'danger',
    ];

    public function statusLabel(): string {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function statusColor(): string {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeFilter($query, $request) {
         if (isset($request['month'])) {
            $query->where('month', $request['month']);
        }

        if (isset($request['branch_id'])) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('branch_id', $request['branch_id']);
            });
        }

        if (isset($request['department_id'])) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request['department_id']);
            });
        }
        if (isset($request['status'])) {
            $query->where('status', $request['status']);
        }
        return $query;
    }
}

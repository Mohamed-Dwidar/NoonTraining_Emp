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

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

<?php

namespace Modules\PayrollModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Payroll extends Model {
    use HasFactory;

    protected $guarded = [];

    public const TRANSFER_PENDING = 'pending';
    public const TRANSFER_SENT    = 'sent';

    public const TRANSFER_STATUSES = [
        self::TRANSFER_PENDING => 'لم يتم التحويل',
        self::TRANSFER_SENT    => 'تم التحويل',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    public function isTransferred(): bool {
        return $this->transfer_status === self::TRANSFER_SENT;
    }

    public function transferStatusLabel(): string {
        return self::TRANSFER_STATUSES[$this->transfer_status] ?? self::TRANSFER_STATUSES[self::TRANSFER_PENDING];
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeFilter($query, $request = []) {
        if (!empty($request['month'])) {
            $query->where('month', $request['month']);
        }

        if (!empty($request['employee_id'])) {
            $query->where('employee_id', $request['employee_id']);
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

        //check if the employee is active
        $query->whereHas('employee', function ($q) {
            $q->whereIn('status', ['active', 'on_leave']);
        });

        return $query->with('employee');
    }
}

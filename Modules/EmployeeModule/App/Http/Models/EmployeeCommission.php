<?php

namespace Modules\EmployeeModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CommissionModule\App\Http\Models\Commission;

class EmployeeCommission extends Model {
    protected $table = 'employee_commissions';
    public $timestamps = false;
    protected $guarded = [];

    public function employee() {
        return $this->belongsTo(Employee::class, 'student_id');
    }

    public function commission() {
        return $this->belongsTo(Commission::class, 'commission_id');
    }
}

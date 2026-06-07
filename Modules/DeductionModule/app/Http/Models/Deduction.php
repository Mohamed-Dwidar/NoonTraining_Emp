<?php

namespace Modules\DeductionModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\EmployeeModule\App\Http\Models\Employee;

class Deduction extends Model {
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

<?php

namespace Modules\ViolationModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationRepeat extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'deduction_amount' => 'decimal:2',
    ];

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }
}

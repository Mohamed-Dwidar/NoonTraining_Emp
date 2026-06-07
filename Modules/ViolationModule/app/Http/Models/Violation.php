<?php

namespace Modules\ViolationModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Violation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function repeats()
    {
        return $this->hasMany(ViolationRepeat::class)->orderBy('repeat_number');
    }
}

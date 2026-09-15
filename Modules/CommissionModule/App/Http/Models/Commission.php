<?php

namespace Modules\CommissionModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilter($query, $request = [])
    {
        // Filter by commission name
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }
        return $query;
    }
}

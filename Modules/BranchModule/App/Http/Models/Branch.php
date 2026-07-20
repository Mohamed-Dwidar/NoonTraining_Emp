<?php

namespace Modules\BranchModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserModule\App\Http\Models\User;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFilter($query, $request = [])
    {
        // Filter by branch name
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }
        return $query;
    }
}

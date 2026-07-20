<?php

namespace Modules\DepartmentModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\BranchModule\App\Http\Models\Branch;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function scopeFilter($query, $request = [])
    {
        // Filter by department name
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }

        // Filter by branch_id
        if (isset($request['branch_id'])) {
            $query->where('branch_id', $request['branch_id']);
        }
        return $query;
    }
}

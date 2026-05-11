<?php

namespace Modules\PermissionModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelHasRoles extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    // protected static function newFactory()
    // {
    //     return \Modules\PermissionModule\Database\factories\ModelHasRolesFactory::new();
    // }
}

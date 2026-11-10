<?php

namespace Modules\UserModule\App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasFactory, HasApiTokens, SoftDeletes, HasRoles;

    /**
     * Spatie permissions are checked on the employee guard (admins bypass via Gate::before
     * in PermissionModuleServiceProvider). Without this, spatie would pick the first guard
     * using this model ('web').
     */
    protected $guard_name = 'employee';

    protected $fillable = ['name', 'email', 'password'];

    public function userable() {
        return $this->morphTo();
    }
}

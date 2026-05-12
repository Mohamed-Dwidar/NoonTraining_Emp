<?php

namespace Modules\UserModule\App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

// use Spatie\Permission\Models\Permission;
// use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Relations\MorphToMany;
class User extends Authenticatable {
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = ['name', 'email', 'password'];

    public function userable() {
        return $this->morphTo();
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role) {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if the user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission) {
        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Get roles associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany(
            'Modules\PermissionModule\Entities\Role',
            'model_has_roles',
            'model_id',
            'role_id'
        )->wherePivot('model_type', self::class);
    }

    /**
     * Get permissions associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions() {
        return $this->belongsToMany(
            'Modules\PermissionModule\Entities\Permission',
            'model_has_permissions',
            'model_id',
            'permission_id'
        )->withPivot('model_type')
            ->wherePivot('model_type', self::class);
    }


    /**

     * Get all permissions for the user, including those from roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllPermissions() {
        // Direct permissions assigned to the user
        $directPermissions = $this->permissions;

        // Permissions inherited from roles
        $rolePermissions = $this->roles->flatMap(function ($role) {
            return $role->permissions;
        });

        // Merge and return unique permissions
        return $directPermissions->merge($rolePermissions)->unique('id');
    }
}

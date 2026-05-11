<?php
namespace Modules\PermissionModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\PermissionModule\Entities\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    /**
     * Relationship: Roles associated with the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_has_permissions',
            'permission_id',
            'role_id'
        );
    }
}

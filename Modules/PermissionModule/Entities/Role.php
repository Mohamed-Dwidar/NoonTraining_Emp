<?php
namespace Modules\PermissionModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\PermissionModule\Entities\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    /**
     * Relationship: Permissions associated with the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }
}

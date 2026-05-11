<?php
namespace Modules\PermissionModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelHasPermission extends Model
{
    use HasFactory;

    protected $fillable = ['permission_id', 'model_type', 'model_id'];

    /**
     * Relationship: Permission associated with the model.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Relationship: The model (e.g., User) that the permission belongs to.
     */
    public function model()
    {
        return $this->morphTo();
    }
}

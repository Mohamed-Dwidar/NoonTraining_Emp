<?php

namespace Modules\PermissionModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelHasRole extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'model_type', 'model_id'];

    /**
     * Relationship: Role associated with the model.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relationship: The model (e.g., User) that the role belongs to.
     */
    public function model()
    {
        return $this->morphTo();
    }
}

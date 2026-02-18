<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLogs extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'id',
        'admin_id',
        'action',
        'model_type',
        'model_id',
        'details',
    ];

    // The attributes that should be cast.
    // protected $casts = [
    //     'admin_id' => 'integer',
    //     'model_id' => 'integer',
    //     'details' => 'string',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

    // Get the model that was affected by the action (User or NGO).
    public function auditable(): MorphTo
    {
        return $this->morphTo('auditable', 'model_type', 'model_id');
    }

    // Scope a query to filter by action type.
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    // Scope a query to filter by model type.
    public function scopeModelType($query, string $modelType)
    {
        return $query->where('model_type', $modelType);
    }
}

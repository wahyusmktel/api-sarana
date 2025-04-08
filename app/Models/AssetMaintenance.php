<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetMaintenance extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'asset_id',
        'maintenance_type',
        'performed_by',
        'cost',
        'description',
        'performed_at',
        'photo_before',
        'photo_after',
        'document_name',
        'document_path',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}

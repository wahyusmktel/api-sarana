<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetDocument extends Model
{
    use HasFactory;

    protected $table = 'asset_documents';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'asset_id',
        'file_path',
        'file_name',
        'document_type',
        'uploaded_at',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}

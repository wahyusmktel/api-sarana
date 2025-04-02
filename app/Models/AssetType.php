<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetType extends Model
{
    use HasFactory;

    protected $table = 'asset_types';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'category_id',
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(AssetCategory::class);
    }
}

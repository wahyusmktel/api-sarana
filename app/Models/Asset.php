<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'asset_code',
        'name',
        'serial_number',
        'category_id',
        'type_id',
        'condition_id',
        'location_id',
        'acquisition_date',
        'acquisition_cost',
        'funding_source',
        'is_active',
        'responsible_user_id',
        'qr_code_path',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class);
    }

    public function type()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function condition()
    {
        return $this->belongsTo(AssetCondition::class);
    }

    public function location()
    {
        return $this->belongsTo(AssetLocation::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}

<?php

// app/Models/AssetUsage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetUsage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asset_usages';

    protected $fillable = [
        'asset_id',
        'user_id',
        'usage_type',
        'description',
        'used_at',
        'finished_at',
        'condition_after'
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'finished_at' => 'datetime'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

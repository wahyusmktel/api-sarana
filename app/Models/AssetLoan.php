<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetLoan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asset_loans';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'asset_id',
        'borrower_user_id',
        'purpose',
        'loan_start',
        'loan_end',
        'status',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'loan_start' => 'date',
        'loan_end' => 'date',
        'returned_at' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_user_id');
    }
}

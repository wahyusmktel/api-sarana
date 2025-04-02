<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'full_name',
        'gender',
        'birth_date',
        'phone',
        'address',
        'avatar_url',
        'bio',
        'status'
    ];

    // Tambahkan boot method untuk menghasilkan UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}

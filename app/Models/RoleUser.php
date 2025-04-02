<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'role_user';

    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_at',
    ];
}

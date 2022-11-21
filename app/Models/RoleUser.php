<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_users';
    protected $primaryKey = 'id_role_user';

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_role_user',
        'id_role',
        'id_user',
        'a_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'id_updater',
    ];
}

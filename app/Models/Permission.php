<?php
// app/Models/Permission.php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    const NAME = 'name';
    const GUARD_NAME = 'guard_name';
    const MODULE_ID = 'module_id';
    const DESCRIPTION = 'description';

    protected $fillable = [
        self::NAME,
        self::GUARD_NAME,
        self::MODULE_ID,
        self::DESCRIPTION,
    ];

    public function modules()
    {
        return $this->belongsTo(Module::class);
    }

    public function usersWithExtra()
    {
        return $this->belongsToMany(User::class, 'user_extra_permissions')
                    ->withPivot('granted')
                    ->withTimestamps();
    }
}

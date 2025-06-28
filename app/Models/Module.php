<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Base
{
    use HasFactory;

    const NAME = 'name';
    const SLUG = 'slug';
    const DESCRIPTION = 'description';
    const IS_ACTIVE = 'is_active';

    protected $fillable = [
        self::ID,
        self::NAME,
        self::SLUG,
        self::DESCRIPTION,
        self::IS_ACTIVE,
    ];

    protected $casts = [
        self::IS_ACTIVE => 'boolean',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module_id');
    }
}

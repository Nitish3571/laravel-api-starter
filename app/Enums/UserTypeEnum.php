<?php

namespace App\Enums;

class UserTypeEnum
{
    const SUPER_ADMIN   = 1;

    const ADMIN = 2;

    const USER = 3;


    const SUPER_ADMIN_TYPE_NAME = 'super_admin';
    const ADMIN_TYPE_NAME = 'admin';
    const USER_TYPE_NAME = 'user';
    const USER_TYPE_NAMES = [
        self::SUPER_ADMIN_TYPE_NAME,
        self::ADMIN_TYPE_NAME,
        self::USER_TYPE_NAME,
    ];
    const USER_TYPE = [
        self::SUPER_ADMIN,
        self::ADMIN,
        self::USER,
    ];
    public static function values()
    {
        return self::USER_TYPE;
    }
    public static function names()
    {
        return self::USER_TYPE_NAMES;
    }
}

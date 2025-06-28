<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Base
{
    use HasFactory;
    const ID = 'id';
    const USER_ID = 'user_id';
    const IP_ADDRESS = 'ip_address';
    const USER_AGENT = 'user_agent';
    const LOGIN_AT = 'login_at';
    const LOGOUT_AT = 'logout_at';
    const DEVICE_TYPE = 'device_type';
    const LOCATION = 'location';

    protected $fillable = [
        self::USER_ID,
        self::IP_ADDRESS,
        self::USER_AGENT,
        self::LOGIN_AT,
        self::LOGOUT_AT,
        self::DEVICE_TYPE,
        self::LOCATION,
    ];

    protected $casts = [
        self::LOGIN_AT => 'datetime',
        self::LOGOUT_AT => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }
}

<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    // Column constants
    const ID = 'id';
    const NAME = 'name';
    const EMAIL = 'email';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
    const PASSWORD = 'password';
    const USER_TYPE = 'user_type';
    const STATUS = 'status';
    const PHONE = 'phone';
    const DATE_OF_BIRTH = 'date_of_birth';
    const BIO = 'bio';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $translatable = [self::BIO];

    protected $fillable = [
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::USER_TYPE,
        self::STATUS,
        self::PHONE,
        self::DATE_OF_BIRTH,
        self::BIO,
    ];

    protected $hidden = [
        self::PASSWORD,
        'remember_token',
    ];

    protected $casts = [
        self::EMAIL_VERIFIED_AT => 'datetime',
        self::PASSWORD => 'hashed',
        self::DATE_OF_BIRTH => 'date',
        self::BIO => 'array',
    ];

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function extraPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_extra_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatars', 'thumb') ?: null;
    }

    // public function courses()
    // {
    //     return $this->belongsToMany(Course::class, 'course_enrollments');
    // }

    // public function createdCourses()
    // {
    //     return $this->hasMany(Course::class, Course::CREATED_BY);
    // }
}

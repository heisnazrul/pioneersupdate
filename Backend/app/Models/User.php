<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    public const STATUSES = [
        'active',
        'inactive',
        'banned',
    ];

    public const ROLES = [
        'admin',
        'team',
        'counsellor',
        'uni_agent',
        'agent',
        'lg_agent',
        'school',
        'lg_student',
        'uni_student',
    ];

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'role',
        'phone',
        'avatar',
        'status',
        'last_login_at',
        'password',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function otps()
    {
        return $this->hasMany(UserOtp::class);
    }

    public function wishlists()
    {
        return $this->hasMany(UniversityWishlist::class);
    }

    public function languageCourseWishlists()
    {
        return $this->hasMany(LanguageCourseWishlist::class);
    }

    public function languageCourseCompares()
    {
        return $this->hasMany(LanguageCourseCompare::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const STATUSES = ['active', 'inactive', 'banned'];

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

    public const HIGH_LEVEL_ROLES = [
        'admin',
        'team',
        'counsellor',
        'agent',
        'school',
    ];

    public const LOW_LEVEL_ROLES = [
        'lg_student',
        'lg_agent',
        'uni_student',
        'uni_agent',
    ];

    public const APP_COURSEENGLISH = 'courseenglish';
    public const APP_UNIVERSITY = 'university';

    public const FRONTEND_APPS = [
        self::APP_COURSEENGLISH,
        self::APP_UNIVERSITY,
    ];

    public const APP_ROLE_MAP = [
        self::APP_COURSEENGLISH => ['lg_student', 'lg_agent'],
        self::APP_UNIVERSITY => ['uni_student', 'uni_agent'],
    ];

    protected $fillable = [
        'name', 'email', 'username', 'role', 'phone',
        'avatar', 'status', 'last_login_at', 'password', 'google_id',
        'referral_code', 'referred_by_type', 'referred_by_user_id', 'referred_by_agent_id',
        'referral_code_used', 'referred_at', 'referral_commission_balance', 'referral_commission_total',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_login_at'     => 'datetime',
            'referred_at'       => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    public function otps()
    {
        return $this->hasMany(UserOtp::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    public function staffAssignments()
    {
        return $this->hasMany(StaffStudentAssignment::class, 'student_user_id');
    }

    public function assignedStudents()
    {
        return $this->hasMany(StaffStudentAssignment::class, 'staff_user_id');
    }

    public function universityWishlists()
    {
        return $this->hasMany(UniversityWishlist::class);
    }

    public function scholarshipApplications()
    {
        return $this->hasMany(ScholarshipApplication::class);
    }

    public function assignedRoleSlugs(): Collection
    {
        $slugs = $this->relationLoaded('roles')
            ? $this->roles->pluck('slug')
            : $this->roles()->pluck('slug');

        if ($this->role) {
            $slugs = $slugs->push($this->role);
        }

        return $slugs->filter()->unique()->values();
    }

    public function hasRole(string $role): bool
    {
        return $this->assignedRoleSlugs()->contains($role);
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->assignedRoleSlugs()->intersect($roles)->isNotEmpty();
    }

    public function isFrontendUser(): bool
    {
        return $this->hasAnyRole(self::LOW_LEVEL_ROLES);
    }

    public function isWebOnlyUser(): bool
    {
        return $this->hasAnyRole(self::HIGH_LEVEL_ROLES) && !$this->isFrontendUser();
    }

    public function canAccessCourseEnglish(): bool
    {
        return $this->hasAnyRole(self::APP_ROLE_MAP[self::APP_COURSEENGLISH]);
    }

    public function canAccessUniversity(): bool
    {
        return $this->hasAnyRole(self::APP_ROLE_MAP[self::APP_UNIVERSITY]);
    }

    public function primaryFrontendRoleForApp(string $app): ?string
    {
        $roles = $this->assignedRoleSlugs()->all();

        $precedence = match ($app) {
            self::APP_COURSEENGLISH => ['lg_agent', 'lg_student'],
            self::APP_UNIVERSITY => ['uni_agent', 'uni_student'],
            default => [],
        };

        foreach ($precedence as $role) {
            if (in_array($role, $roles, true)) {
                return $role;
            }
        }

        return null;
    }

    public function assignPrimaryRole(string $role): void
    {
        $newRole = Role::query()->where('slug', $role)->first();

        if (!$newRole) {
            return;
        }

        $previousRole = $this->role;

        if ($previousRole && $previousRole !== $role) {
            $previousRoleId = Role::query()->where('slug', $previousRole)->value('id');

            if ($previousRoleId) {
                $this->roles()->detach($previousRoleId);
            }
        }

        $this->roles()->syncWithoutDetaching([$newRole->id]);
        $this->forceFill(['role' => $role])->saveQuietly();
        $this->unsetRelation('roles');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'team'], true);
    }
}

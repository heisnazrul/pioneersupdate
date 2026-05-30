<?php

namespace App\Services\Staff;

use App\Models\Role;
use App\Models\StaffStudentAssignment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffStudentService
{
    public function paginate(Request $request, ?int $staffUserId = null): LengthAwarePaginator
    {
        $search = trim((string) $request->query('search', ''));
        $counsellorId = $request->query('counsellor_id');

        $query = User::query()
            ->where('role', 'lg_student')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($staffUserId, function ($q) use ($staffUserId) {
                $q->where(function ($inner) use ($staffUserId) {
                    $inner->whereIn('id', function ($sub) use ($staffUserId) {
                        $sub->select('student_user_id')
                            ->from('staff_student_assignments')
                            ->where('staff_user_id', $staffUserId);
                    })->orWhereIn('id', function ($sub) use ($staffUserId) {
                        $sub->select('user_id')
                            ->from('language_course_bookings')
                            ->where('assigned_to', $staffUserId)
                            ->whereNotNull('user_id');
                    })->orWhereIn('id', function ($sub) use ($staffUserId) {
                        $sub->select('user_id')
                            ->from('online_course_bookings')
                            ->where('assigned_to', $staffUserId)
                            ->whereNotNull('user_id');
                    });
                });
            })
            ->when($counsellorId, function ($q) use ($counsellorId) {
                $q->whereIn('id', function ($sub) use ($counsellorId) {
                    $sub->select('student_user_id')
                        ->from('staff_student_assignments')
                        ->where('staff_user_id', $counsellorId);
                });
            })
            ->latest('id');

        return $query->paginate(25)->withQueryString();
    }

    public function countForStaff(?int $staffUserId = null): int
    {
        if ($staffUserId === null) {
            return User::query()->where('role', 'lg_student')->count();
        }

        return User::query()
            ->where('role', 'lg_student')
            ->where(function ($q) use ($staffUserId) {
                $q->whereIn('id', function ($sub) use ($staffUserId) {
                    $sub->select('student_user_id')
                        ->from('staff_student_assignments')
                        ->where('staff_user_id', $staffUserId);
                });
            })
            ->count();
    }

    public function createStudent(User $staff, array $data, string $source = 'created'): User
    {
        return DB::transaction(function () use ($staff, $data, $source) {
            $student = User::create([
                'name' => $data['name'],
                'email' => strtolower($data['email']),
                'phone' => $data['phone'] ?? null,
                'status' => 'active',
                'role' => 'lg_student',
                'password' => Hash::make(Str::random(24)),
            ]);

            $roleId = Role::query()->where('slug', 'lg_student')->value('id');
            if ($roleId) {
                $student->roles()->syncWithoutDetaching([$roleId]);
            }

            StaffStudentAssignment::firstOrCreate(
                [
                    'staff_user_id' => $staff->id,
                    'student_user_id' => $student->id,
                ],
                [
                    'assigned_by' => $staff->id,
                    'source' => $source,
                ],
            );

            return $student;
        });
    }

    public function assignStudent(int $studentUserId, int $counsellorId, User $assignedBy): StaffStudentAssignment
    {
        return StaffStudentAssignment::updateOrCreate(
            [
                'staff_user_id' => $counsellorId,
                'student_user_id' => $studentUserId,
            ],
            [
                'assigned_by' => $assignedBy->id,
                'source' => 'manual',
            ],
        );
    }
}

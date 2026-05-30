<?php

namespace App\Services\Agent;

use App\Models\Agent;
use App\Models\AgentStudent;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AgentBookingResolver
{
    /**
     * @return array{0: User, 1: int, 2: int}
     */
    public function resolveStudentForAgent(User $agentUser, int $agentStudentId): array
    {
        if (! $agentUser->hasRole('lg_agent')) {
            throw ValidationException::withMessages([
                'agent_student_id' => ['Only agents can book on behalf of students.'],
            ]);
        }

        $agent = Agent::query()->where('user_id', $agentUser->id)->first();
        if (! $agent) {
            throw ValidationException::withMessages([
                'agent_student_id' => ['Agent profile not found.'],
            ]);
        }

        $agentStudent = AgentStudent::query()
            ->where('agent_id', $agent->id)
            ->where('id', $agentStudentId)
            ->first();

        if (! $agentStudent) {
            throw ValidationException::withMessages([
                'agent_student_id' => ['Selected student does not belong to this agent.'],
            ]);
        }

        if (! $agentStudent->student_user_id) {
            throw ValidationException::withMessages([
                'agent_student_id' => ['This student account is not fully set up yet.'],
            ]);
        }

        $studentUser = User::query()->find($agentStudent->student_user_id);
        if (! $studentUser) {
            throw ValidationException::withMessages([
                'agent_student_id' => ['Student user account not found.'],
            ]);
        }

        return [$studentUser, $agent->id, $agentUser->id];
    }

    public function isAgent(User $user): bool
    {
        return $user->hasRole('lg_agent');
    }
}

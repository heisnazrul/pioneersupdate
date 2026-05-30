<?php

namespace App\Services\Staff;

use Illuminate\Http\Request;

class StaffBookingWizardSession
{
    public const KEY = 'counsellor_booking_wizard';

    /** @return list<string> */
    public function stepNames(?array $data = null): array
    {
        $data ??= $this->all();
        $type = $data['booking_type'] ?? 'language_course';

        if ($type === 'online_course') {
            return ['type', 'school', 'course', 'student', 'schedule', 'review'];
        }

        return ['type', 'school', 'branch', 'course', 'student', 'schedule', 'extras', 'review'];
    }

    public function stepLabels(?array $data = null): array
    {
        $labels = [
            'type' => 'Type & category',
            'school' => 'School',
            'branch' => 'Branch',
            'course' => 'Course',
            'student' => 'Student',
            'schedule' => 'Schedule',
            'extras' => 'Extras',
            'review' => 'Review',
        ];

        return array_map(fn ($name) => $labels[$name] ?? $name, $this->stepNames($data));
    }

    public function all(): array
    {
        return session(self::KEY, $this->defaults());
    }

    public function defaults(): array
    {
        return [
            'mode' => 'booking',
            'booking_type' => 'language_course',
            'category_id' => '',
            'school_id' => '',
            'branch_id' => '',
            'course_id' => '',
            'student_user_id' => '',
            'weeks' => 4,
            'start_date' => '',
            'acc_age' => 18,
            'display_currency' => 'SAR',
            'accommodation_id' => 'no-acc',
            'pickup_id' => '',
            'insurance_ids' => [],
            'notes' => '',
        ];
    }

    public function put(array $values): void
    {
        session([self::KEY => array_merge($this->all(), $values)]);
    }

    public function reset(?string $mode = 'booking'): void
    {
        $defaults = $this->defaults();
        $defaults['mode'] = $mode ?? 'booking';
        session([self::KEY => $defaults]);
    }

    public function stepNumber(string $stepName, ?array $data = null): int
    {
        $index = array_search($stepName, $this->stepNames($data), true);

        return $index === false ? 1 : $index + 1;
    }

    public function stepName(int $stepNumber, ?array $data = null): ?string
    {
        $names = $this->stepNames($data);

        return $names[$stepNumber - 1] ?? null;
    }

    public function totalSteps(?array $data = null): int
    {
        return count($this->stepNames($data));
    }

    public function mergeFromRequest(Request $request, string $stepName): void
    {
        $payload = match ($stepName) {
            'type' => [
                'mode' => $request->input('mode', $this->all()['mode']),
                'booking_type' => $request->input('booking_type', 'language_course'),
                'category_id' => $request->input('category_id', ''),
            ],
            'school' => ['school_id' => $request->input('school_id')],
            'branch' => ['branch_id' => $request->input('branch_id')],
            'course' => ['course_id' => $request->input('course_id')],
            'student' => ['student_user_id' => $request->input('student_user_id')],
            'schedule' => [
                'weeks' => max(1, (int) $request->input('weeks', 1)),
                'start_date' => $request->input('start_date', ''),
                'acc_age' => (int) $request->input('acc_age', 18),
                'display_currency' => strtoupper($request->input('display_currency', 'SAR')),
            ],
            'extras' => [
                'accommodation_id' => $request->input('accommodation_id', 'no-acc'),
                'pickup_id' => $request->input('pickup_id', ''),
                'insurance_ids' => array_values(array_map('intval', $request->input('insurance_ids', []))),
            ],
            'review' => ['notes' => $request->input('notes', '')],
            default => [],
        };

        if ($stepName === 'type') {
            $previous = $this->all();
            if (($previous['booking_type'] ?? '') !== ($payload['booking_type'] ?? '')) {
                $payload = array_merge($this->defaults(), [
                    'mode' => $payload['mode'],
                    'booking_type' => $payload['booking_type'],
                    'category_id' => $payload['category_id'],
                ]);
            }
        }

        $this->put($payload);
    }
}

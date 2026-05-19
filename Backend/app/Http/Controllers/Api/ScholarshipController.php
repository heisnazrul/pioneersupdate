<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Cache;

class ScholarshipController extends Controller
{
    private function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    public function show(string $slug)
    {
        $cacheKey = 'scholarship_show_' . $slug;

        $response = Cache::remember($cacheKey, now()->addWeek(), function () use ($slug) {
            $scholarship = Scholarship::where('slug', $slug)
                ->where('is_active', true)
                ->with(['university'])
                ->firstOrFail();

            $amountDisplay = '';
            if ($scholarship->amount_type === 'fixed') {
                $amountDisplay = $scholarship->currency . number_format((float) $scholarship->amount_value);
            } elseif ($scholarship->amount_type === 'percentage') {
                $amountDisplay = $scholarship->amount_value . '% Tuition';
            } else {
                $amountDisplay = 'Variable';
                if ($scholarship->min_amount && $scholarship->max_amount) {
                    $amountDisplay = $scholarship->currency . number_format((float) $scholarship->min_amount) . ' - ' . number_format((float) $scholarship->max_amount);
                } elseif ($scholarship->max_amount) {
                    $amountDisplay = 'Up to ' . $scholarship->currency . number_format((float) $scholarship->max_amount);
                }
            }

            return [
                'id' => $scholarship->id,
                'name' => $scholarship->name,
                'ar_name' => $scholarship->ar_name,
                'slug' => $scholarship->slug,
                'provider_name' => $scholarship->provider_name,
                'ar_provider_name' => $scholarship->ar_provider_name,
                'summary' => $scholarship->summary,
                'ar_summary' => $scholarship->ar_summary,
                'description' => $scholarship->description,
                'ar_description' => $scholarship->ar_description,
                'amount_display' => $amountDisplay,
                'deadline' => $scholarship->deadline_date ? $scholarship->deadline_date->format('d F Y') : 'Rolling',
                'eligible_nationalities' => $scholarship->eligible_nationalities,
                'eligibility_text' => $scholarship->eligibility_text,
                'ar_eligibility_text' => $scholarship->ar_eligibility_text,
                'apply_link' => $scholarship->apply_link,
                'tags' => $scholarship->tags,
                'university' => $scholarship->university ? [
                    'name' => $scholarship->university->name,
                    'ar_name' => $scholarship->university->ar_name,
                    'slug' => $scholarship->university->slug,
                    'logo' => $this->getImageUrl($scholarship->university->logo),
                ] : null,
            ];
        });

        return response()->json($response);
    }
}

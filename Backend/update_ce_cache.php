<?php

$files = glob('/Users/macbookpro/Desktop/pioneersedufull/Backend/app/Http/Controllers/Api/CourseEnglish*.php');

// We exclude SubmissionController which is transactional
$files = array_filter($files, function($f) {
    return !str_contains($f, 'SubmissionController.php') && !str_contains($f, 'InteractionController.php');
});

foreach ($files as $filePath) {
    $content = file_get_contents($filePath);
    $basename = basename($filePath, '.php');
    $shortName = strtolower(str_replace(['CourseEnglish', 'Controller'], '', $basename));

    // Ensure Cache facade exists
    if (!str_contains($content, 'use Illuminate\Support\Facades\Cache;')) {
        $content = preg_replace('/use Illuminate\\\\Http\\\\Request;/', "use Illuminate\Http\Request;\nuse Illuminate\Support\Facades\Cache;", $content);
        if (!str_contains($content, 'use Illuminate\Support\Facades\Cache;')) {
            $content = preg_replace('/use App\\\\Http\\\\Controllers\\\\Controller;/', "use App\Http\Controllers\Controller;\nuse Illuminate\Support\Facades\Cache;", $content);
        }
    }
    
    // Ensure helper to check isArabic is present if not and requests are passed
    if (!str_contains($content, 'private function isArabic(')) {
        $trait = <<<CODE
    private function isArabic(Request \$request): bool
    {
        \$lang = strtolower((string) (\$request->query('lang') ?: \$request->header('X-Lang') ?: \$request->cookie('uni_language')));
        if (\$lang === 'ar') return true;
        \$accept = strtolower((string) \$request->header('Accept-Language', ''));
        return \\Illuminate\\Support\\Str::startsWith(\$accept, 'ar');
    }
CODE;
        // Insert trait after class declaration
        $content = preg_replace('/(class\s+[A-Za-z0-9_]+\s+extends\s+Controller\s*\{)(.*?)/m', "$1\n$trait\n$2", $content, 1);
        
        // Ensure Str facade exists if we added the trait
        if (!str_contains($content, 'use Illuminate\Support\Str;')) {
           $content = preg_replace('/use Illuminate\\\\Http\\\\Request;/', "use Illuminate\Http\Request;\nuse Illuminate\Support\Str;", $content);
        }
    }

    $methodsHandled = 0;
    
    // Define patterns for methods returning json
    $pattern = '/(public function\s+([a-zA-Z0-9_]+)\s*\((.*?)\)(?:\s*:\s*JsonResponse)?\s*\{)(.*?)(^\s*return response\(\)->json\((.*?)\);)(.*?^\})/msm';
    
    $content = preg_replace_callback($pattern, function($matches) use ($shortName, &$methodsHandled) {
        $signatureAndOpenBracket = $matches[1];
        $methodName = $matches[2];
        $args = $matches[3];
        $body = $matches[4]; // body up to return
        $returnStmt = $matches[5]; // `return response()->json(`
        $returnData = $matches[6]; // the stuff inside json(), might be multi-line
        $restOfMethod = $matches[7]; // `); }` etc

        if (in_array($methodName, ['galleryEntryToUrl', 'normalizeGalleryValue', 'getBranchCoverImage', 'cmsPayloadForSlug', 'toPublicUrl'])) {
           return $matches[0];
        }

        $methodsHandled++;
        $hasRequestVar = str_contains($args, 'Request $request');
        $hasSlugVar = str_contains($args, '$slug');

        $cacheKey = "'ce_{$shortName}_{$methodName}_' . \$lang";
        $hashQueryStr = "";
        
        $closureUseArgs = [];

        if ($hasRequestVar) {
            $hashQueryStr = " . '_' . md5(json_encode(\$request->all()))";
            $closureUseArgs[] = '$request';
        }
        
        if ($hasSlugVar) {
            $hashQueryStr .= " . '_' . \$slug";
            $closureUseArgs[] = '$slug';
        }

        $closureUseArgs[] = '$isArabic';
        $closureUseStr = implode(', ', $closureUseArgs);

        $cacheKeysStr = "";
        if ($hasRequestVar) {
             $cacheKeysStr .= "\n        \$isArabic = \$this->isArabic(\$request);\n        \$lang = \$isArabic ? 'ar' : 'en';\n        \$cacheKey = {$cacheKey}{$hashQueryStr};";
        } else {
             // For methods like public function cms() without request, but usually we need request to know language
             $cacheKeysStr .= "\n        \$isArabic = request() ? \$this->isArabic(request()) : false;\n        \$lang = \$isArabic ? 'ar' : 'en';\n        \$cacheKey = {$cacheKey}{$hashQueryStr};";
             $body = preg_replace('/\$request->/', 'request()->', $body);
        }

        $newBody = <<<REPLACEMENT
$signatureAndOpenBracket$cacheKeysStr

        \$response = Cache::remember(\$cacheKey, now()->addWeek(), function () use ($closureUseStr) {
$body
            return $returnData;
        });

        return response()->json(\$response);
    }
REPLACEMENT;
        return $newBody;
    }, $content);

    file_put_contents($filePath, $content);
    echo "Updated $basename ($methodsHandled methods)\n";
}

echo "All files processed.\n";

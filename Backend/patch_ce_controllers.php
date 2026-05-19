<?php

function patchController($filePath) {
    if (!file_exists($filePath)) return false;
    
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
    
    // Ensure helper to check isArabic is present
    if (!str_contains($content, 'private function isArabic(')) {
        $trait = <<<CODE
    private function isArabic(Request \$request): bool
    {
        \$lang = strtolower((string) (\$request->query('lang') ?: \$request->header('X-Lang') ?: \$request->cookie('uni_language')));
        if (\$lang === 'ar') return true;
        \$accept = strtolower((string) \$request->header('Accept-Language', ''));
        return \Illuminate\Support\Str::startsWith(\$accept, 'ar');
    }
CODE;
        $content = preg_replace('/(class\s+[A-Za-z0-9_]+\s+extends\s+Controller\s*\{)(.*?)/m', "$1\n$trait\n$2", $content, 1);
        
        if (!str_contains($content, 'use Illuminate\Support\Str;')) {
           $content = preg_replace('/use Illuminate\\\\Http\\\\Request;/', "use Illuminate\Http\Request;\nuse Illuminate\Support\Str;", $content);
        }
    }

    $methodsHandled = 0;
    
    // Parse using tokenizer for safety against unclosed braces
    $tokens = token_get_all($content);
    $inMethod = false;
    $methodName = '';
    $braceCount = 0;
    $methodStartTokenIdx = -1;
    $methodBodyStartIdx = -1;
    $isPublic = false;
    $signatureTokens = [];
    
    $methodsToPatch = [];
    
    for ($i = 0; $i < count($tokens); $i++) {
        $token = $tokens[$i];
        
        if (is_array($token) && $token[0] == T_PUBLIC) {
            $isPublic = true;
        }
        
        if ($isPublic && is_array($token) && $token[0] == T_FUNCTION) {
            $inMethod = true;
            $braceCount = 0;
            $methodStartTokenIdx = $i;
            $signatureTokens = [];
            continue;
        }
        
        if ($inMethod && $braceCount === 0) {
            $signatureTokens[] = $token;
            if (is_array($token) && $token[0] == T_STRING && empty($methodName)) {
                $methodName = $token[1];
            }
            if ($token === '{') {
                $braceCount++;
                $methodBodyStartIdx = $i;
            }
        } elseif ($inMethod && $braceCount > 0) {
            if ($token === '{' || (is_array($token) && ($token[0] == T_CURLY_OPEN || $token[0] == T_DOLLAR_OPEN_CURLY_BRACES))) {
                $braceCount++;
            } elseif ($token === '}') {
                $braceCount--;
                if ($braceCount === 0) {
                    $inMethod = false;
                    $isPublic = false;
                    
                    if (!in_array($methodName, ['galleryEntryToUrl', 'normalizeGalleryValue', 'getBranchCoverImage', 'cmsPayloadForSlug', 'toPublicUrl'])) {
                        $methodsToPatch[] = [
                            'name' => $methodName,
                            'startIdx' => $methodStartTokenIdx,
                            'bodyStartIdx' => $methodBodyStartIdx,
                            'endIdx' => $i,
                            'sigTokens' => $signatureTokens
                        ];
                    }
                    $methodName = '';
                }
            }
        }
    }
    
    // Process backwards to maintain indices!
    for ($m = count($methodsToPatch) - 1; $m >= 0; $m--) {
        $patch = $methodsToPatch[$m];
        $methodName = $patch['name'];
        $sigStr = '';
        foreach ($patch['sigTokens'] as $t) {
            $sigStr .= is_array($t) ? $t[1] : $t;
        }
        
        $hasRequest = str_contains($sigStr, 'Request');
        $hasSlug = str_contains($sigStr, '$slug');
        
        $bodyStr = '';
        // Extract body
        for ($t = $patch['bodyStartIdx'] + 1; $t < $patch['endIdx']; $t++) {
            $bodyStr .= is_array($tokens[$t]) ? $tokens[$t][1] : $tokens[$t];
        }
        
        // Find return statement inside body
        if (preg_match('/^\s*return\s+response\(\)->json\((.*?)\);?\s*$/ms', $bodyStr, $bodyMatches)) {
            $innerReturn = $bodyMatches[1];
            $bodyLines = preg_replace('/^\s*return\s+response\(\)->json\((.*?)\);?\s*$/ms', '', $bodyStr);
            
            $cacheKey = "'ce_{$shortName}_{$methodName}_' . \$lang";
            $hashQueryStr = "";
            $closureUseArgs = [];
            
            if ($hasRequest) {
                $hashQueryStr = " . '_' . md5(json_encode(\$request->all()))";
                $closureUseArgs[] = '$request';
            }
            if ($hasSlug) {
                $hashQueryStr .= " . '_' . \$slug";
                $closureUseArgs[] = '$slug';
            }
            $closureUseArgs[] = '$isArabic';
            $closureUseStr = implode(', ', $closureUseArgs);

            $newBody = "";
            if ($hasRequest) {
                 $newBody .= "\n        \$isArabic = \$this->isArabic(\$request);\n        \$lang = \$isArabic ? 'ar' : 'en';\n        \$cacheKey = {$cacheKey}{$hashQueryStr};\n";
            } else {
                 $newBody .= "\n        \$isArabic = request() ? \$this->isArabic(request()) : false;\n        \$lang = \$isArabic ? 'ar' : 'en';\n        \$cacheKey = {$cacheKey}{$hashQueryStr};\n";
                 $bodyLines = preg_replace('/\$request->/', 'request()->', $bodyLines);
            }
            
            $newBody .= "\n        \$response = Cache::remember(\$cacheKey, now()->addWeek(), function () use ($closureUseStr) {\n" . $bodyLines . "\n            return $innerReturn;\n        });\n\n        return response()->json(\$response);\n    ";
            
            // Replace in actual code string!
            $sigFullStr = 'public function ' . trim($sigStr);
            $oldMethodFull = $sigFullStr . '{' . $bodyStr . '}';
            $newMethodFull = $sigFullStr . '{' . $newBody . '}';
            
            $content = str_replace($oldMethodFull, $newMethodFull, $content);
            $methodsHandled++;
        }
    }
    
    file_put_contents($filePath, $content);
    return $methodsHandled;
}

$files = glob('/Users/macbookpro/Desktop/pioneersedufull/Backend/app/Http/Controllers/Api/CourseEnglish*.php');
$files = array_filter($files, function($f) {
    return !str_contains($f, 'SubmissionController.php') && !str_contains($f, 'InteractionController.php');
});

foreach ($files as $f) {
    if (str_contains($f, 'CourseEnglishListingController') || str_contains($f, 'CourseEnglishHomeController')) {
        echo basename($f) . ": " . patchController($f) . " methods.\n";
    }
}

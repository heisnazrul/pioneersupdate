<?php
$file = '/Users/macbookpro/Desktop/pioneersedufull/Backend/app/Http/Controllers/Api/CourseEnglishHomeController.php';
$content = file_get_contents($file);

function patchMethod($content, $methodName, $bodyStartRegex, $bodyEndRegex, $replacementTemplate) {
    if (preg_match('/(public function ' . $methodName . '\s*\(\)(?:\s*:\s*JsonResponse)?\s*\{)(.*?)(^\s*return response\(\)->json\((.*?)\);)(.*?^\})/msm', $content, $matches)) {
        
        $signature = "public function $methodName(Request \$request): JsonResponse\n    {";
        $body = $matches[2];
        $returnData = $matches[4];
        
        $cacheKeysStr = "\n        \$isArabic = \$this->isArabic(\$request);\n        \$lang = \$isArabic ? 'ar' : 'en';\n        \$cacheKey = 'ce_home_{$methodName}_' . \$lang;";
        $closureStr = "\n        \$response = Cache::remember(\$cacheKey, now()->addWeek(), function () use (\$request, \$isArabic) {";
        
        $newMethod = $signature . $cacheKeysStr . $closureStr . $body . "            return " . $returnData . ";\n        });\n\n        return response()->json(\$response);\n    }";
        
        $content = str_replace($matches[0], $newMethod, $content);
    }
    return $content;
}

$methods = ['cms', 'online', 'summer', 'trainingCourses', 'blogs', 'certificates', 'reviews', 'faqs'];
foreach ($methods as $m) {
    $content = patchMethod($content, $m, '', '', '');
}

file_put_contents($file, $content);
echo "Done\n";

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminCacheFlush;
use Illuminate\Http\RedirectResponse;

class CacheController extends Controller
{
    public function flush(): RedirectResponse
    {
        $steps = AdminCacheFlush::flushAll();

        return back()->with(
            'success',
            'Cache flushed: ' . implode(' · ', $steps)
        );
    }
}

<?php

namespace App\Http\Controllers\Front;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\Front\PostService;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke(PostService $service, Request $request)
    {
        return Inertia::render('Front/Index', [
            'posts' => $service->getActivePosts($request),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function show(Post $post)
    {
        return Inertia::render(
            'Front/Posts/Show',
            [
                'post' => $post
            ]
        );
    }
}

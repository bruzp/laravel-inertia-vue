<?php

namespace App\Services\Front;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PostService
{
    public function getActivePosts(Request $request): LengthAwarePaginator
    {
        return Post::query()
            ->select([
                'posts.id',
                'posts.user_id',
                'posts.title',
                'posts.slug',
                'posts.is_active',
                'posts.featured_image',
                'posts.created_at',
                'posts.updated_at',
                'users.id as userid',
                'users.name as username',
            ])
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('is_active', 1)
            ->orderByDesc('updated_at')
            ->paginate(10);
    }
}

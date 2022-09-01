<?php

namespace App\Services\Dashboard;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function datatable(Request $request): array
    {
        $search = $request->filled('search') ? $request->search : NULL;
        $sort_field = $request->filled('field') ? $request->field : 'updated_at';
        $sort_direction = $request->filled('direction') ? $request->direction : 'desc';

        $posts = Post::query()
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
            ->when($search, function ($query, $search) {
                $query->search('title', $search);
                $query->orSearch('slug', $search);
                $query->orSearch('content', $search);
            })
            ->when($sort_field && $sort_direction, function ($query) use ($sort_field, $sort_direction) {
                $query->orderBy($sort_field, $sort_direction);
            })
            ->paginate(10)
            ->through(function ($post) {
                $post->permissions = [
                    'create' => Auth::user()->can('create', Post::class),
                    'edit' => Auth::user()->can('update', $post),
                    'delete' => Auth::user()->can('delete', $post),
                    'publish' => Auth::user()->can('publish', $post),
                    'unpublish' => Auth::user()->can('unpublish', $post),
                ];

                $post->slug_limited = Str::of($post->slug)->limit(15);
                $post->title_limited = Str::of($post->title)->limit(15);
                $post->username_limited = Str::of($post->username)->limit(15);

                return $post;
            })
            ->withQueryString();

        return [
            'posts' => $posts,
            'filters' => [
                'search' => $search,
                'field' => $sort_field,
                'direction' => $sort_direction,
            ]
        ];
    }

    public function storeFeaturedImage(Post $post, Request $request): string
    {
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $this->removePreviousFeaturedImage($post);

            return Storage::disk('public')->putfile('posts/' . $post->id, $request->file('featured_image'));
        }

        return '';
    }

    public function updatePostFeaturedImage(Post $post, string $path): void
    {
        if ($path) {
            $post->update([
                'featured_image' => $path
            ]);
        }
    }

    public function removePreviousFeaturedImage(Post $post): void
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
    }
}

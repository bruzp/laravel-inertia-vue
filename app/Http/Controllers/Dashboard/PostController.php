<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\Dashboard\PostService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Request $request, PostService $service)
    {
        $request->validate([
            'field' => ['nullable', 'in:id,title,slug,created_at,username,updated_at'],
            'direction' => ['nullable', 'in:asc,desc'],
            'search' => ['nullable'],
        ]);

        return Inertia::render(
            'Dashboard/Posts/Index',
            $service->datatable($request)
        );
    }

    public function create()
    {
        return Inertia::render(
            'Dashboard/Posts/Create'
        );
    }

    public function store(StorePostRequest $request, PostService $service)
    {
        $post = Post::create($request->safe()->except('featured_image'));

        $path = $service->storeFeaturedImage($post, $request);

        $service->updatePostFeaturedImage($post, $path);

        return redirect()->route('posts.index')->with('message', 'Post Created Successfully');
    }

    public function edit(Post $post)
    {
        return Inertia::render(
            'Dashboard/Posts/Edit',
            [
                'post' => $post
            ]
        );
    }

    public function update(Post $post, UpdatePostRequest $request, PostService $service)
    {
        $post->update($request->safe()->except('featured_image'));

        $path = $service->storeFeaturedImage($post, $request);

        $service->updatePostFeaturedImage($post, $path);

        return redirect()->route('posts.index')->with('message', 'Post Updated Successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('message', 'Post Delete Successfully');
    }

    public function publish(Post $post, Request $request)
    {
        if ($request->user()->cannot('publish', $post)) {
            abort(404);
        }

        $request->validate([
            'is_active' => 'required|integer|in:0,1',
        ]);

        $post->update([
            'is_active' => $request->is_active
        ]);

        return redirect()->route('posts.index')->with('message', sprintf('Post %s Successfully', $request->is_active ? 'Published' : 'Unpublished'));
    }
}

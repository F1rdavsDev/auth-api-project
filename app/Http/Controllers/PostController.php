<?php 
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('user')->latest()->get();
    }

    public function store(PostStoreRequest $request)
    {
        return Post::create($request->validated() + ['user_id' => auth()->id()]);
    }

    public function show(Post $post)
    {
        return $post->load('user');
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            return response()->json(['message' => 'Ruxsat yo‘q'], 403);
        }

        $post->update($request->validated());
        return $post;
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            return response()->json(['message' => 'Ruxsat yo‘q'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function dashboard()
    {
        $posts = Post::with('category', 'user')
                    ->where('is_published', 1)
                    ->latest()
                    ->get();

        return view('posts.dashboard', compact('posts'));
    }

    public function index(Request $request)
    {
        $posts = Post::with('category', 'user')
                    ->withCount('likes')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->is_published = 0;

        if ($request->hasFile('thumbnail')) {
            $imageName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $request->file('thumbnail')->move(public_path('post_thumbnail'), $imageName);
            $post->thumbnail = 'post_thumbnail/' . $imageName;
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post berhasil ditambahkan!');
    }

    public function detail($id)
    {
        $post = Post::with('category', 'user')->findOrFail($id);
        return view('posts.detail', compact('post'));
    }

    public function showAll($id)
    {
        $post = Post::with('category', 'user')->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $post = Post::findOrFail($id);
        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail && file_exists(public_path('post_thumbnail/' . $post->thumbnail))) {
                unlink(public_path('post_thumbnail/' . $post->thumbnail));
            }

            $imageName = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
            $request->file('thumbnail')->move(public_path('post_thumbnail'), $imageName);
            $post->thumbnail = 'post_thumbnail/' . $imageName;
        }

        $post->category_id = $request->category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post berhasil diperbarui!');
    }

    public function publish($id)
    {
        $post = Post::findOrFail($id);
        
        // Toggle is_published (0 → 1 atau 1 → 0)
        $post->is_published = !$post->is_published;
        $post->save();

        return response()->json([
            'success' => true,
            'is_published' => $post->is_published
        ]);
    }

    public function like(Request $request, Post $post)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Cek jika user adalah admin, maka tidak bisa like
        if ($user->role === 'admin') {
            return response()->json(['error' => 'Admin tidak dapat memberikan like'], 401);
        }

        // Cek apakah user sudah menyukai post ini
        $like = Like::where('post_id', $post->id)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'likes_count' => $post->likes()->count()]);
        }

        // Tambahkan like
        Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return response()->json(['liked' => true, 'likes_count' => $post->likes()->count()]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->thumbnail && file_exists(public_path('post_thumbnail/' . $post->thumbnail))) {
            unlink(public_path('post_thumbnail/' . $post->thumbnail));
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post berhasil dihapus!');
    }
}

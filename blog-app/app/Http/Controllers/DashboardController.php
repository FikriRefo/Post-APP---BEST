<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $users = User::where('role', '!=', 'admin')->get();

        $posts = Post::with('category', 'user')->withCount('likes')->where('is_published', 1);

        if ($request->category) {
            $posts->where('category_id', $request->category);
        }
        if ($request->user) {
            $posts->where('user_id', $request->user);
        }
        if ($request->search) {
            $posts->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        if ($request->sort_by) {
            $sortOptions = [
                'title_asc' => ['title', 'asc'],
                'title_desc' => ['title', 'desc'],
                'date_newest' => ['created_at', 'desc'],
                'date_oldest' => ['created_at', 'asc']
            ];
            if (isset($sortOptions[$request->sort_by])) {
                $posts->orderBy($sortOptions[$request->sort_by][0], $sortOptions[$request->sort_by][1]);
            }
        } else {
            $posts->latest();
        }

        $posts = $posts->paginate(9);

        return view('dashboard.index', compact('posts', 'categories', 'users'));
    }


    public function show($id)
    {
        $user = Auth::user();
        $profile = optional($user)->profile;

        $post = Post::with([
            'category', 
            'user.profile', 
            'comments.replies.user.profile'
        ])->findOrFail($id);

        // Increment the views count for the post
        if (!$user || ($user->id !== $post->user_id && $user->role !== 'admin')) {
            $post->increment('views');
        }

        // $post->increment('views');

        return view('dashboard.show', compact('post', 'user', 'profile'));
    }

}

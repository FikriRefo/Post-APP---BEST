<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalPosts = Post::count();
        $totalCategories = Category::count();
        $postsBycategories = Category::withCount('posts')->get();
        $categories = Category::all();

        // Top 3 Postingan dengan Likes Terbanyak
        $topPosts = Post::with('user', 'category')
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();

        // Top 3 Postingan dengan Viewers Terbanyak
        $topViewedPosts = Post::with('user', 'category')
            ->orderBy('views', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'categories', 'totalUsers', 'totalPosts', 'totalCategories',
            'postsBycategories', 'topPosts', 'topViewedPosts'
        ));
    }

}

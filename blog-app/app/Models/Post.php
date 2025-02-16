<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'title', 'content', 'thumbnail', 'is_published', 'views'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedByUser()
    {
        $userId = Auth::id(); // Pastikan Auth digunakan dengan benar
        if (!$userId) {
            return false; // Jika tidak ada pengguna yang login, kembalikan false
        }

        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}


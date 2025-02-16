<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'full_name', 'avatar', 'gender'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

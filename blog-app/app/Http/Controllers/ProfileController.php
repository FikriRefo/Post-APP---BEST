<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;
        

        return view('profile.show', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user = Auth::user();
        $profile = $user->profile ?: new Profile(['user_id' => $user->id]);

        $profile->full_name = $request->full_name;
        $profile->gender = $request->gender;

        // Simpan gambar di public/avatars dan hapus avatar lama jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($profile->avatar && file_exists(public_path('avatars/' . $profile->avatar))) {
                unlink(public_path('avatars/' . $profile->avatar));
            }

            // Simpan avatar baru
            $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->move(public_path('avatars'), $avatarName);
            $profile->avatar = $avatarName;
        }

        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}


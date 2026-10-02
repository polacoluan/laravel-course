<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        return view('profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $data = $request->validated();
        /**
         * @var UploadedFile $file
         */
        if ($file = $request->photo) {

            $data['photo'] = $file->store('photos', 'public');
        }

        /**
         * @var User $user
         */
        $user = Auth::user();

        $user->fill($data)->save();

        return back()->with('message', 'Profile updated successfully');
    }
}

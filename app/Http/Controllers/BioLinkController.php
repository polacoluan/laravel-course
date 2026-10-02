<?php

namespace App\Http\Controllers;

use App\Models\User;

class BioLinkController extends Controller
{
    public function __invoke(User $user)
    {
        $user->load(['links' => fn ($query) => $query->orderBy('sort')->orderBy('id')]);

        return view('bio-links', compact('user'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinkRequest $request)
    {
        DB::transaction(function () use ($request): void {
            $user = $request->user()->newQuery()->lockForUpdate()->findOrFail($request->user()->id);
            $user->links()->create([
                ...$request->validated(),
                'sort' => (int) $user->links()->max('sort') + 1,
            ]);
        });

        return to_route('dashboard');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        return view('links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLinkRequest $request, Link $link)
    {
        $link->fill($request->validated())->save();

        return to_route('dashboard')->with('message', 'Alterada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $link->delete();

        return to_route('dashboard')->with('message', 'Deletado com sucesso!');
    }

    public function up(Link $link)
    {
        $link->moveUp();

        return back();
    }

    public function down(Link $link)
    {
        $link->moveDown();

        return back();
    }
}

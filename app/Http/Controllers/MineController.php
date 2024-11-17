<?php

namespace App\Http\Controllers;

use App\Models\Mine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MineController extends Controller
{
    public function index()
    {
        return Inertia::render('Mines/Index');
    }

    public function mines()
    {
        $mines = Mine::all();
        foreach ($mines as $mine) {
            $mine->status = $mine->status == 1 ? 'Active' : 'Inactive';
        }
        return $mines;
    }

    public function store(Request $request)
    {
        $request->validate([
            'mine_name' => 'required|string|max:254',
            'mine_no' => 'required|string|max:254',
            'mine_place' => 'required|string|max:254',
            'security' => 'required|integer',
            'status' => 'required',
        ]);

        $mine = $request->id ? Mine::findOrFail($request->id) : new Mine;
        $mine->id = $mine->id ?? Str::orderedUuid();

        $mine->mine_name = $request->mine_name;
        $mine->mine_no = $request->mine_no;
        $mine->mine_place = $request->mine_place;
        $mine->security = $request->security;

        $mine->status = $request->status === "true" ? 1 : 0;

        $mine->save();

        return 'success';
    }

    public function show($id)
    {
        $mine = Mine::findOrFail($id);

        $mine->status = $mine->status == 1 ? true : false;

        return $mine;

    }

    public function delete($id)
    {
        $mine = Mine::findOrFail($id);
        $mine->delete();
        return 'success';
    }
}

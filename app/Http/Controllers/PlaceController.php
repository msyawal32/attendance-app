<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index()
{
    $places = Place::with('user')->get();
    $users = User::all();
    return view('places.index', compact('places', 'users'));
}


    public function create()
    {
        $users = User::all(); // Fetch all users
        return view('places.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'residence' => 'required',
            'block' => 'required',
        ]);

        Place::create($request->all());

        return redirect()->route('places.index')->with('success', 'Place created!');
    }

    public function edit(Place $place)
    {
        $users = User::all(); // Fetch all users for the dropdown
        return view('places.edit', compact('place', 'users'));
    }

    public function update(Request $request, Place $place)
    {
        $request->validate([
            'user_id' => 'required',
            'residence' => 'required',
            'block' => 'required',
        ]);

        $place->update($request->all());

        return redirect()->route('places.index')->with('success', 'Place updated successfully');
    }

    public function destroy(Place $place)
    {
        $place->delete();

        return redirect()->route('places.index')->with('success', 'Place deleted successfully');
    }
}

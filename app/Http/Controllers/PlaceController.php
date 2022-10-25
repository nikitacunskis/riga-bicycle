<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use Illuminate\Support\Facades\Redirect;
use App\Models\Place;
use Inertia\Inertia;
use App\Services\PlaceService;
use DB;

class PlaceController extends Controller
{
    
    /**
     * list page
     */
    public function index()
    {
        $placeCollection = Place::all();
        return Inertia::render('Places/List', ['places' => $placeCollection]);
    }
    /**
     * create page
     */
    public function create()
    {
        return Inertia::render('Places/Create');
    }

    /**
     * edit page
     */
    public function edit(int $id)
    {
        $place = Place::find($id);
        return Inertia::render('Places/Edit', ['place' => $place]);
    }

    /**
     * destroy
     */
    public function destroy(int $id)
    {
        $place = Place::find($id);
        $place->delete();
        return Redirect::route('dashboard.places');
    }

    /**
     * Store Place in storage.
     */
    public function store(StorePlaceRequest $request)
    {
        $place = new Place($request->validated());
        $place->save();
        return Redirect::route('dashboard.places');
    }
    /**
     * Update Place in storage.
     */
    public function update(UpdatePlaceRequest $request, int $id)
    {
        $place = Place::find($id);
        $place->update($request->validated());
        return Redirect::route('dashboard.places');
    }
}

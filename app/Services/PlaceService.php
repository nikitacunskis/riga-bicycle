<?php 

class PlaceService {

    public function getPlaces()
    {
        $placeCollection = Place::all();
        return json_encode($placeCollection->toArray());
    }
}
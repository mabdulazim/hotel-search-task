<?php

namespace App\Services;

// TRANSFORMERS
use App\Transformers\HotelTransformer;

class HotelService
{   
    public function getHotels($dateFrom, $dateTo, $city, $adultsCount)
    {
        $hotelsData = [];
        $providersNames = HotelFactory::providers; // GET OUR PROVIDER

        foreach ($providersNames as $providerName) 
        {
            $provider   = HotelFactory::getHotels($providerName); // GET NEW OBJECT FROM PROVIDER CLASS
            $hotels     = $provider->getHotelsData($dateFrom, $dateTo, $city, $adultsCount); // GET HOTELS DATA FROM PROVIDER
            $hotelsData = array_merge($hotelsData, $hotels); // PUSH NEW HOTELS DATA TO OUR ARRAY
        }

        // SORT HOTELS BY RATE DESCENDING
        usort($hotelsData, function($a, $b)
        {
            return $a['rate'] < $b['rate'];
        });

        // MAP RESPONSE THROUGH TRANSFORMER TO REMOVE rate FROM HOTEL OBJECT
        $hotelsData = fractal($hotelsData, new HotelTransformer())->toArray();

        return $hotelsData['data'];
    }
}

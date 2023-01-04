<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Statistic;
use Illuminate\Http\Request;

class CovidStatsController extends Controller
{
    public function index(): \Illuminate\Database\Eloquent\Collection
    {
        /*return Country::with('statistics')
            ->get()
            ->transform(fn($country) => [
                'code' => $country->code,
                'name' => json_decode($country->name),
                'confirmed' => $country->statistics->confirmed,
                'recovered' => $country->statistics->recovered,
                'death' => $country->statistics->death,
            ]);*/

        return Country::all();
    }

    public function statistic($id){
        $country = Country::find($id);

        if (!isset($country))
            abort(422, 'Country not found');

        return $country->statistics;
    }

    public function statistics(): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        return response(Statistic::all());
    }
}

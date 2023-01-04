<?php

namespace App\Http\Controllers;

use App\Console\Commands\RetrieveCountries;
use App\Jobs\RetrieveCovid19Stats;
use App\Models\Country;
use App\Models\Statistic;
use App\Models\User;
use App\Services\Covid19Stats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Octane\Facades\Octane;

class TestController extends Controller
{

    public function index(){
        sleep(3);
        return 5;
    }
}

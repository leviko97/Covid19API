<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\Covid19Stats;
use Illuminate\Console\Command;

class RetrieveCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid19:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve countries';

    public function handle()
    {
        $countries = (new Covid19Stats())->getCountries()->json();

        foreach ($countries as $country){
            Country::firstOrNew(
                ['code' => $country['code']],
                ['name' => json_encode($country['name'])]
            )->save();
        }
    }
}

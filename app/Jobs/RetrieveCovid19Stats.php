<?php

namespace App\Jobs;

use App\Models\Country;
use App\Models\Statistic;
use App\Services\Covid19Stats;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Laravel\Octane\Facades\Octane;

class RetrieveCovid19Stats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        foreach (Country::lazy() as $country)
            $this->setStat($country);
    }
    /*public function handle()
    {
        Country::chunk(6, function($countries) {
            $tasks = [];

            foreach ($countries as $country)
                $tasks[] = fn() => $this->setStat($country);

            Octane::concurrently($tasks);
        });
    }*/

    protected function setStat($country){
        try {
            $stat = (new Covid19Stats())->getStatistic($country->code)->json();

            Statistic::firstOrNew(
                ['country_id' => $country->id],
                [
                    'confirmed' => $stat['confirmed'],
                    'recovered' => $stat['recovered'],
                    'death' => $stat['deaths'],
                ]
            )->save();
        }catch (\Exception $exception){
            Log::error('Could not retrieve '.$country->code.' statistic');
        }
    }
}

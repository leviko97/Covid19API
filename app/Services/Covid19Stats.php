<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use \Illuminate\Http\Client\Response;

class Covid19Stats extends Http
{
    protected string $endpoint = 'https://devtest.ge';

    protected function call($method, $uri, $data=null): ?Response
    {
        return Http::retry(3, 100)->$method($this->endpoint.$uri, $data);
    }

    public function getCountries(): Response
    {
        return $this->call('get', '/countries');
    }

    public function getStatistic($country): Response
    {
        return $this->call('post', '/get-country-statistics', ['code' => $country]);
    }
}

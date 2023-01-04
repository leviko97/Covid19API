<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class Covid19Test extends TestCase
{
    public function test_trying_to_get_countries_as_a_guest(){
        $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/api/countries')
            ->assertStatus(401);
    }

    public function test_get_countries(){
        Sanctum::actingAs(User::factory()->create());

        Country::factory(10)->create();

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/api/countries');

        $response->assertOk();
        $response->assertJsonCount(Country::count());
    }

    public function test_get_statistic(){
        Sanctum::actingAs(User::factory()->create());

        $country = Country::factory()->create();
        Statistic::factory()->create([
            'country_id' => $country->id
        ]);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->get('/api/countries/'.$country->id.'/statistic');

        $response->assertOk();
        $response->assertJsonFragment([
            'confirmed' => $country->statistics->confirmed
        ]);
    }
}

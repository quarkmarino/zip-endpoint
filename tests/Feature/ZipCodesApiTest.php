<?php

namespace Tests\Feature;

use App\Models\CodigoPostal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZipCodesApiTest extends TestCase
{
    /**
     * Tests that the endpoint responds in less than 300ms
     *
     * @return void
     */
    public function test_endpoint_responds_in_less_than_300_ms()
    {
        $randomZipCodes = CodigoPostal::inRandomOrder()->take(5)->pluck('d_codigo');

        $randomZipCodes->each(function ($zipCode) {
            $start = microtime(true);

            $response = Http::get('https://backbonesystems.marianoescalera.me/api/zip-codes/' . $zipCode);
            // $response = $this->get('/api/zip-codes/85203');

            $end = microtime(true);
            $duration = ($end - $start) * 1000;

            $this->assertLessThan(300, $duration);

            $response->assertStatus(200);
        });
    }

    /**
     *
     *
     * @return void
     */
    // public function test_endpoint_responds_is_exactly_as_reference_api()
    // {
    //     $randomZipCodes = CodigoPostal::inRandomOrder()->take(5)->pluck('d_codigo');

    //     $randomZipCodes->each(function ($zipCode) {
    //         $response = Http::get('http://jobs.backbonesystems.io/api/zip-codes/' . $zipCode);
    //         $response = $this->get('/api/zip-codes/' . $zipCode);

    //         $response->assertStatus(200)->assertJson($response->json());
    //     });
    // }
}

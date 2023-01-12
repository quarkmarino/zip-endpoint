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
     * Tests that the endpoint responds in less than 300ms internally
     *
     * @return void
     */
    public function test_endpoint_avg_responds_in_less_than_300_ms_internally()
    {
        $i = 1;

        $avgDuration = CodigoPostal::inRandomOrder()
            ->take(50)
            ->pluck('d_codigo')
            ->push('85203')
            ->avg(function ($zipCode) use (&$i) {
                $start = microtime(true);

                $response = $this->get('/api/zip-codes/' . $zipCode);

                $end = microtime(true);
                $duration = ($end - $start) * 1000;

                // dump("{$i}: {$zipCode} internal request duration {$duration}");
                $i++;
                $response->assertStatus(200);

                return $duration;
            });

        dump("Internal test Avg. duration: {$avgDuration}");

        $this->assertLessThan(300, $avgDuration);
    }

    /**
     * Tests that the endpoint responds in less than 300ms externally
     *
     * @return void
     */
    public function test_endpoint_avg_responds_in_less_than_300_ms_externally()
    {
        $i = 1;
        $avgDuration = CodigoPostal::inRandomOrder()
            ->take(49)
            ->pluck('d_codigo')
            ->push('85203')
            ->avg(function ($zipCode) use (&$i) {
                $start = microtime(true);

                $response = Http::get(env('APP_URL') . '/api/zip-codes/' . $zipCode);

                $end = microtime(true);
                $duration = ($end - $start) * 1000;
                dump("{$i}: {$zipCode} external request duration {$duration}");
                $i++;
                // $response->assertStatus(200);
                return $duration;
            });

        dump("External test Avg. duration: {$avgDuration}");

        $this->assertLessThan(300, $avgDuration);
    }

    /**
     *
     *
     * @return void
     */
    public function test_endpoint_responds_is_exactly_as_reference_api()
    {
        $randomZipCodes = CodigoPostal::inRandomOrder()->take(5)->pluck('d_codigo');

        $randomZipCodes->each(function ($zipCode) {
            $zipCode = 85203;

            $response = Http::get('http://jobs.backbonesystems.io/api/zip-codes/' . $zipCode);

            $response = $this->get('/api/zip-codes/' . $zipCode);

            $response->assertStatus(200)->assertJson($response->json(), true);
            usleep(rand(100, 1000));
        });
    }
}

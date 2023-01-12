<?php

namespace Tests\Feature;

use App\Models\CodigoPostal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExternalZipCodesApiTest extends TestCase
{
    /**
     * Tests that the endpoint responds in less than 300ms externally
     *
     * @return void
     */
    public function test_endpoint_avg_responds_in_less_than_300_ms_externally()
    {
        $i = 1;
        $avgDuration = collect(["92002","29807","71833","31034","73005","70529","78410","69516","52793","54090","61840","46475","79840","68569","61150","73996","48284","59420","99150","83905","72492","99674","73319","52020","42743","41679","60435","95750","62668","83138","32446","67608","84177","43816","81706","58676","82150","79343","87694","65554","60294","38757","09360","26020","75386","81218","79033","38465","48185","85203"])
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
}

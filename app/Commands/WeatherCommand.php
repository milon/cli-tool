<?php

namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class WeatherCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'weather {place=Berlin}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Weather forcast for a place.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $place = $this->argument('place');
        $url = "http://wttr.in/$place";

        $response = (new Client)->get($url)->getBody();

        $this->line($response);
    }

}

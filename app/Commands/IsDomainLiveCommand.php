<?php

namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class IsDomainLiveCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'domain {name}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check whether the domain is live or not.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = preg_replace("/https?:\/\//", '', $this->argument('name'));
        $url = "https://isitup.org/$name.json";

        $response = (new Client)->get($url)->getBody();
        $data = json_decode($response);

        if(!$this->isValid($data)) {
            $this->error("$name is not a valid domain.");
            return;
        }

        if(!$this->isLive($data)) {
            $this->error("$name is not live.");
            return;
        }

        $this->info("$name is live.");
        $this->line("IP address: {$data->response_ip}");
        $this->line("Response time: {$data->response_time}");
        return;
    }

    /**
     * check whether the domain is valid or not
     *
     * @param  array  $data
     * @return boolean
     */
    private function isValid($data)
    {
        return ($data->status_code !== 3 && checkdnsrr($data->domain, 'A')) ? true : false;
    }

    /**
     * check whether the domain is live
     *
     * @param  array  $data
     * @return boolean
     */
    private function isLive($data)
    {
        return ($data->status_code === 1) ? true : false;
    }
}

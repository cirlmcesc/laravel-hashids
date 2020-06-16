<?php

namespace Cirlmcesc\LaravelHashids\Commands;

use Illuminate\Console\Command;

class HashidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hashids:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing ID after hash or hash id.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $value = $this->ask('Please enter a value');

        $action = $this->choice('Need encryption or decryption?', [
            "encryption",
            "decryption",
        ], 0);

        $this->info($action == "encryption"
            ? hashids($value)
            : hashidsdecode($value));
    }
}
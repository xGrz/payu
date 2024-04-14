<?php

namespace xGrz\PayU\Commands;

use Illuminate\Console\Command;

class PublishConfigCommand extends Command
{
    protected $signature = 'payu:publish-config';
    protected $description = 'Publishes only configuration file in your config directory (payu.php)';

    public function handle(): int
    {
        $this->newLine();
        $this->call('vendor:publish', ['--tag' => 'payu-config']);
        $this->newLine();

        return Command::SUCCESS;

    }
}


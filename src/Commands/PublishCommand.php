<?php

namespace xGrz\PayU\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'payu:publish';
    protected $description = 'Publishes configuration and language files';

    public function handle(): int
    {
        $this->newLine();
        $this->call('vendor:publish', ['--tag' => 'payu-config']);
        $this->call('vendor:publish', ['--tag' => 'payu-lang']);
        $this->newLine();

        return Command::SUCCESS;

    }
}


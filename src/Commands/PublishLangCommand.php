<?php

namespace xGrz\PayU\Commands;

use Illuminate\Console\Command;

class PublishLangCommand extends Command
{
    protected $signature = 'payu:publish-lang';
    protected $description = 'Publishes only language files (in case of use builtin controllers)';

    public function handle(): int
    {
        $this->newLine();
        $this->call('vendor:publish', ['--tag' => 'payu-lang']);
        $this->newLine();

        return Command::SUCCESS;
    }
}


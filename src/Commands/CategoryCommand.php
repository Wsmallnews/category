<?php

namespace Wsmallnews\Category\Commands;

use Illuminate\Console\Command;

class CategoryCommand extends Command
{
    public $signature = 'category';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

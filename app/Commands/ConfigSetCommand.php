<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\ConfigService;
use LaravelZero\Framework\Commands\Command;

final class ConfigSetCommand extends Command
{
    protected $signature = 'config:set
        {key : Configuration key to set}
        {value : Value to set}';

    protected $description = 'Set a configuration value';

    public function handle(ConfigService $configService): int
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        if (! $configService->isValidKey($key)) {
            $this->error("Invalid configuration key: {$key}");
            $this->line('Valid keys: '.implode(', ', $configService->validKeys()));

            return self::FAILURE;
        }

        if (in_array($key, ['md', 'showWatermark'], true)) {
            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        $configService->set($key, $value);

        $this->info("Set {$key} = ".var_export($value, true));

        return self::SUCCESS;
    }
}

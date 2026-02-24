<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\ConfigService;
use LaravelZero\Framework\Commands\Command;

final class ConfigShowCommand extends Command
{
    protected $signature = 'config:show';

    protected $description = 'Show current configuration';

    public function handle(ConfigService $configService): int
    {
        if (! $configService->exists()) {
            $this->warn('No configuration found. Run "config:init" to create one.');
            $this->line("  Expected path: {$configService->configPath()}");

            return self::SUCCESS;
        }

        $config = $configService->all();
        $validKeys = $configService->validKeys();

        $rows = [];
        foreach ($validKeys as $key) {
            $value = $config[$key] ?? null;

            if ($value === null) {
                $displayValue = '(not set)';
            } elseif (is_bool($value)) {
                $displayValue = $value ? 'true' : 'false';
            } elseif (is_array($value)) {
                $displayValue = implode(', ', $value) ?: '(empty)';
            } else {
                $displayValue = (string) $value;
            }

            $rows[] = [$key, $displayValue];
        }

        $this->table(['Key', 'Value'], $rows);

        $this->newLine();
        $this->line("Config file: {$configService->configPath()}");

        return self::SUCCESS;
    }
}

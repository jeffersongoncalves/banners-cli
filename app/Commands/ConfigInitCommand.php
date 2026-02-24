<?php

declare(strict_types=1);

namespace App\Commands;

use App\Enums\FileType;
use App\Enums\Pattern;
use App\Enums\Style;
use App\Enums\Theme;
use App\Services\ConfigService;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

final class ConfigInitCommand extends Command
{
    protected $signature = 'config:init';

    protected $description = 'Initialize configuration with interactive wizard';

    public function handle(ConfigService $configService): int
    {
        $this->info('Banners CLI Configuration Wizard');
        $this->newLine();

        if ($configService->exists()) {
            $overwrite = confirm(
                label: 'Configuration already exists. Overwrite?',
                default: false
            );

            if (! $overwrite) {
                $this->info('Configuration unchanged.');

                return self::SUCCESS;
            }
        }

        $theme = select(
            label: 'Select theme:',
            options: collect(Theme::cases())->mapWithKeys(fn (Theme $t) => [$t->value => $t->label()])->all(),
            default: Theme::Light->value,
        );

        $style = select(
            label: 'Select style:',
            options: collect(Style::cases())->mapWithKeys(fn (Style $s) => [$s->value => $s->label()])->all(),
            default: Style::Style1->value,
        );

        $pattern = select(
            label: 'Select background pattern:',
            options: collect(Pattern::cases())->mapWithKeys(fn (Pattern $p) => [$p->value => $p->label()])->all(),
            default: Pattern::CircuitBoard->value,
        );

        $fontSize = text(
            label: 'Font size:',
            default: '96px',
            placeholder: '96px',
        );

        $fileType = select(
            label: 'Default file type:',
            options: collect(FileType::cases())->mapWithKeys(fn (FileType $f) => [$f->value => $f->label()])->all(),
            default: FileType::Png->value,
        );

        $config = [
            'theme' => $theme,
            'style' => $style,
            'pattern' => $pattern,
            'fontSize' => $fontSize,
            'fileType' => $fileType,
        ];

        $configService->init($config);

        $this->newLine();
        $this->info("Configuration saved to: {$configService->configPath()}");

        return self::SUCCESS;
    }
}

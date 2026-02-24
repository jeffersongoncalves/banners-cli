<?php

declare(strict_types=1);

namespace App\Commands;

use App\DTOs\BannerOptions;
use App\Enums\FileType;
use App\Enums\Pattern;
use App\Enums\Style;
use App\Enums\Theme;
use App\Services\BannerService;
use App\Services\ConfigService;
use LaravelZero\Framework\Commands\Command;

final class GenerateCommand extends Command
{
    protected $signature = 'banner:generate
        {name : The banner name/title}
        {output : Output file path (e.g. ./banner.png)}
        {--theme= : Theme (light, dark)}
        {--style= : Style (style_1, style_2)}
        {--pattern= : Background pattern}
        {--fontSize= : Font size (e.g. 96px)}
        {--packageManager= : Package manager text}
        {--packageName= : Package name text}
        {--description= : Banner description}
        {--md : Enable markdown rendering}
        {--showWatermark : Show watermark}
        {--images=* : Image URLs or heroicon names}
        {--fileType= : Output file type (png, jpeg)}';

    protected $description = 'Generate a banner image';

    public function handle(BannerService $bannerService, ConfigService $configService): int
    {
        $config = $configService->all();

        $data = array_filter([
            'name' => $this->argument('name'),
            'theme' => $this->option('theme'),
            'style' => $this->option('style'),
            'pattern' => $this->option('pattern'),
            'fontSize' => $this->option('fontSize'),
            'packageManager' => $this->option('packageManager'),
            'packageName' => $this->option('packageName'),
            'description' => $this->option('description'),
            'md' => $this->option('md') ? '1' : null,
            'showWatermark' => $this->option('showWatermark') ? '1' : null,
            'images' => $this->option('images') ?: null,
            'fileType' => $this->option('fileType'),
        ], fn ($value) => $value !== null && $value !== '' && $value !== []);

        $options = BannerOptions::fromArray(array_merge($config, $data));

        $url = $bannerService->buildUrl($options);
        $outputPath = $this->argument('output');

        $this->info('Generating banner...');
        $this->line("  URL: {$url}");

        try {
            $bannerService->generate($options, $outputPath);

            $this->newLine();
            $this->info("Banner saved to: {$outputPath}");

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to generate banner: {$e->getMessage()}");

            return self::FAILURE;
        }
    }
}

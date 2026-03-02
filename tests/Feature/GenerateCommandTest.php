<?php

use App\Services\BannerService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'image/png'], 'fake-image-data'),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $this->app->instance(BannerService::class, new BannerService($client));
});

it('generates a banner with required arguments', function () {
    $outputPath = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true).'.png';

    $this->artisan('banner:generate', [
        'name' => 'Test Banner',
        'output' => $outputPath,
    ])
        ->expectsOutputToContain('Generating banner...')
        ->expectsOutputToContain('Banner saved to:')
        ->assertExitCode(0);

    expect(file_exists($outputPath))->toBeTrue();
    unlink($outputPath);
});

it('generates a banner with all options', function () {
    $outputPath = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true).'.png';

    $this->artisan('banner:generate', [
        'name' => 'My Project',
        'output' => $outputPath,
        '--theme' => 'dark',
        '--style' => 'style_2',
        '--pattern' => 'texture',
        '--fontSize' => '72px',
        '--packageManager' => 'composer require',
        '--packageName' => 'vendor/package',
        '--description' => 'A great project',
        '--md' => true,
        '--showWatermark' => true,
        '--fileType' => 'png',
    ])
        ->assertExitCode(0);

    expect(file_exists($outputPath))->toBeTrue();
    unlink($outputPath);
});

it('shows error on failure', function () {
    $mock = new MockHandler([
        new \GuzzleHttp\Exception\ConnectException(
            'Connection error',
            new \GuzzleHttp\Psr7\Request('GET', 'test')
        ),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $this->app->instance(BannerService::class, new BannerService($client));

    $outputPath = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true).'.png';

    $this->artisan('banner:generate', [
        'name' => 'Fail',
        'output' => $outputPath,
    ])
        ->expectsOutputToContain('Failed to generate banner')
        ->assertExitCode(1);

    @unlink($outputPath);
});

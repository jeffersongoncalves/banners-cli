<?php

use App\DTOs\BannerOptions;
use App\Enums\FileType;
use App\Enums\Pattern;
use App\Enums\Theme;
use App\Services\BannerService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

describe('BannerService', function () {
    it('builds correct URL', function () {
        $service = new BannerService;
        $options = new BannerOptions(
            name: 'My Banner',
            theme: Theme::Dark,
            pattern: Pattern::Texture,
            description: 'A cool banner',
        );

        $url = $service->buildUrl($options);

        expect($url)->toStartWith('https://banners.beyondco.de/');
        expect($url)->toContain('My%20Banner.png');
        expect($url)->toContain('theme=dark');
        expect($url)->toContain('pattern=texture');
        expect($url)->toContain('description=A+cool+banner');
    });

    it('builds URL with jpeg extension', function () {
        $service = new BannerService;
        $options = new BannerOptions(
            name: 'Test',
            fileType: FileType::Jpeg,
        );

        $url = $service->buildUrl($options);

        expect($url)->toContain('Test.jpeg');
    });

    it('generates and saves banner image', function () {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'image/png'], 'fake-image-content'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new BannerService($client);
        $options = new BannerOptions(name: 'Test');

        $outputPath = sys_get_temp_dir().'/banners-cli-test-'.uniqid('', true).'.png';

        $service->generate($options, $outputPath);

        expect(file_exists($outputPath))->toBeTrue();
        expect(file_get_contents($outputPath))->toBe('fake-image-content');

        unlink($outputPath);
    });

    it('creates output directory if not exists', function () {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'image/png'], 'fake-image-content'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new BannerService($client);
        $options = new BannerOptions(name: 'Test');

        $outputDir = sys_get_temp_dir().'/banners-cli-nested-'.uniqid('', true);
        $outputPath = $outputDir.'/banner.png';

        $service->generate($options, $outputPath);

        expect(is_dir($outputDir))->toBeTrue();
        expect(file_exists($outputPath))->toBeTrue();

        unlink($outputPath);
        rmdir($outputDir);
    });
});

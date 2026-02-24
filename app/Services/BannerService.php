<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\BannerOptions;
use GuzzleHttp\Client;
use RuntimeException;

final class BannerService
{
    private const BASE_URL = 'https://banners.beyondco.de';

    private Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client([
            'timeout' => 30,
            'verify' => false,
        ]);
    }

    public function buildUrl(BannerOptions $options): string
    {
        $name = rawurlencode($options->name);
        $extension = $options->fileType->value;
        $queryString = $options->toQueryString();

        return self::BASE_URL."/{$name}.{$extension}?{$queryString}";
    }

    public function generate(BannerOptions $options, string $outputPath): void
    {
        $url = $this->buildUrl($options);
        $outputDir = dirname($outputPath);

        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $response = $this->client->get($url, [
            'sink' => $outputPath,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException(
                "Failed to generate banner. HTTP status: {$response->getStatusCode()}"
            );
        }

        if (! file_exists($outputPath) || filesize($outputPath) === 0) {
            throw new RuntimeException('Failed to save banner image. File is empty or was not created.');
        }
    }
}

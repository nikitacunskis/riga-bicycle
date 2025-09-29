<?php

namespace App\Services;

use App\Exceptions\MetaPublishException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaPublisherService
{
    public function __construct(
        private string $baseUri = '',
        private string $version = '',
        private string $pageToken = '',
        private string $fbPageId = '',
        private int $timeout = 20,
    ) {
        $cfg = config('services.meta');

        $this->baseUri   = $cfg['base_uri'];
        $this->version   = $cfg['version'];
        $this->pageToken = $cfg['page_token'];
        $this->fbPageId  = $cfg['fb_page_id'];
        $this->timeout   = (int) $cfg['timeout'];
    }

    private function client(): PendingRequest
    {
        return Http::withToken($this->pageToken)
            ->baseUrl("{$this->baseUri}/{$this->version}")
            ->timeout($this->timeout)
            ->asForm()
            ->withUserAgent('RGP-Laravel-MetaPublisher/1.0')
            ->retry(3, 300, function ($exception, $request) {
                // Retry only on 429/5xx
                if (method_exists($exception, 'response') && $exception->response()) {
                    $status = $exception->response()->status();
                    return $status === 429 || ($status >= 500 && $status < 600);
                }
                return false;
            });
    }

    /**
     * Publish a photo to Facebook Page timeline.
     * @return string FB post ID
     * @throws MetaPublishException
     */
    public function publishToFacebookPhoto(string $imageUrl, string $message, bool $dryRun = false): string
    {
        $this->assertConfigured();

        $payload = [
            'url'     => $imageUrl,
            'caption' => $message,
        ];

        if ($dryRun) {
            Log::info('[Meta] FB photo DRY RUN', $payload);
            return 'fb-post(dry-run)';
        }

        $resp = $this->client()->post("/{$this->fbPageId}/photos", $payload);
        $json = $this->okOrThrow($resp, 'Failed to publish FB photo');
        $postId = $json['post_id'] ?? $json['id'] ?? null;

        if (!$postId) {
            $this->throwMeta('FB photo publish returned no id', $json);
        }

        Log::info('[Meta] FB photo published', ['post_id' => $postId, 'fbtrace_id' => $resp->header('x-fb-trace-id')]);
        return $postId;
    }

    /**
     * Publish a text/link post to Facebook Page feed.
     * @return string FB post ID
     * @throws MetaPublishException
     */
    public function publishToFacebookFeed(string $message, ?string $link = null, bool $dryRun = false): string
    {
        $this->assertConfigured();

        $payload = Arr::whereNotNull([
            'message' => $message,
            'link'    => $link,
        ]);

        if ($dryRun) {
            Log::info('[Meta] FB feed DRY RUN', $payload);
            return 'fb-post(dry-run)';
        }

        $resp = $this->client()->post("/{$this->fbPageId}/feed", $payload);
        $json = $this->okOrThrow($resp, 'Failed to publish FB feed post');
        $postId = $json['id'] ?? null;

        if (!$postId) {
            $this->throwMeta('FB feed publish returned no id', $json);
        }

        Log::info('[Meta] FB feed published', ['post_id' => $postId, 'fbtrace_id' => $resp->header('x-fb-trace-id')]);
        return $postId;
    }

    private function okOrThrow($resp, string $fallbackMessage): array
    {
        if ($resp->successful()) {
            return $resp->json() ?? [];
        }

        $json = $resp->json() ?? [];
        $error = $json['error'] ?? [];
        $message = $error['message'] ?? $fallbackMessage;

        $this->throwMeta($message, $json, $resp->status());
    }

    private function throwMeta(string $message, array $json, ?int $httpStatus = null): never
    {
        $error = $json['error'] ?? [];
        $code = $error['code'] ?? $httpStatus;
        $type = $error['type'] ?? null;
        $fbtrace = $error['fbtrace_id'] ?? null;

        Log::warning('[Meta] Graph error', [
            'message' => $message,
            'error' => $error,
            'status' => $httpStatus,
        ]);

        throw new MetaPublishException($message, $code, $type, $fbtrace);
    }

    private function assertConfigured(): void
    {
        foreach (['baseUri','version','pageToken','fbPageId'] as $prop) {
            if (empty($this->$prop)) {
                throw new MetaPublishException("Meta config missing: {$prop}");
            }
        }
    }
}

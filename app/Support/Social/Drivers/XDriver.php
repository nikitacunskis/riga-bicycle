<?php

namespace App\Support\Social\Drivers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use App\Support\Social\Contracts\SocialDriver;
use App\Support\Social\{PostData, PublishResult};
use Throwable;

class XDriver implements SocialDriver
{
    private string $ck; // consumer key
    private string $cs; // consumer secret
    private string $at; // access token
    private string $as; // access token secret

    public function __construct()
    {
        $this->ck = (string) Config::get('larasap.x.consumer_key');
        $this->cs = (string) Config::get('larasap.x.consumer_secret');
        $this->at = (string) Config::get('larasap.x.access_token');
        $this->as = (string) Config::get('larasap.x.access_token_secret');

        foreach (['ck','cs','at','as'] as $k) {
            if ($this->{$k} === '') {
                throw new \RuntimeException("X API credentials missing: {$k}");
            }
        }
    }

    public function publish(PostData $data): PublishResult
    {
        try {
            $tweetUrl = 'https://api.twitter.com/2/tweets';

            $payload = ['text' => $data->text];

            // Handle media (upload first, collect media_ids)
            if (!empty($data->media)) {
                $mediaIds = [];
                $paths = is_array($data->media) ? array_slice($data->media, 0, 4) : [$data->media];
                foreach ($paths as $p) {
                    $local = $this->toLocalPath($p);
                    $mediaIds[] = $this->uploadMedia($local);
                }
                $payload['media'] = ['media_ids' => $mediaIds];
            }

            // OAuth 1.0a User context – for JSON POST, do NOT include body params in signature
            $auth = $this->oauth1HeaderJson('POST', $tweetUrl);

            $res = Http::withHeaders([
                'Authorization' => $auth,
                'Content-Type'  => 'application/json',
            ])->post($tweetUrl, $payload);

            if (!$res->successful()) {
                throw new \RuntimeException("X API Error: ".$res->body());
            }

            $json = $res->json();
            return new PublishResult(true, $json['data']['id'] ?? null, $json);

        } catch (Throwable $e) {
            return new PublishResult(false, null, null, $e->getMessage());
        }
    }

    /**
     * Uploads a single media to Twitter v1.1 and returns media_id_string.
     * Uses base64 'media_data' param. Body is x-www-form-urlencoded, so params are signed.
     */
    private function uploadMedia(string $absolutePath): string
    {
        if (!is_file($absolutePath)) {
            throw new \RuntimeException("X media file not found: {$absolutePath}");
        }

        $uploadUrl = 'https://upload.twitter.com/1.1/media/upload.json';
        $params = ['media_data' => base64_encode(file_get_contents($absolutePath))];

        $auth = $this->oauth1HeaderForm('POST', $uploadUrl, $params);

        $res = Http::withHeaders([
            'Authorization' => $auth,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ])->asForm()->post($uploadUrl, $params);

        if (!$res->successful()) {
            throw new \RuntimeException("X media upload failed: ".$res->body());
        }

        $json = $res->json();
        $id = $json['media_id_string'] ?? null;
        if (!$id) {
            throw new \RuntimeException('X media upload: missing media_id_string');
        }
        return $id;
    }

    /**
     * Resolve to an absolute path; download remote URLs to a temp file.
     */
    private function toLocalPath(string $path): string
    {
        if (preg_match('#^https?://#i', $path)) {
            $tmp = tempnam(sys_get_temp_dir(), 'xmedia_');
            $bin = @file_get_contents($path);
            if ($bin === false) {
                throw new \RuntimeException("Failed to download media: {$path}");
            }
            file_put_contents($tmp, $bin);
            return $tmp;
        }

        // If relative, resolve from base_path()
        if (!str_starts_with($path, '/')) {
            $candidate = base_path($path);
            if (is_file($candidate)) return $candidate;
        }

        if (!is_file($path)) {
            throw new \RuntimeException("Media path does not exist: {$path}");
        }

        return $path;
    }

    /**
     * OAuth 1.0a header for JSON POST (no body params in signature).
     */
    private function oauth1HeaderJson(string $method, string $url): string
    {
        $oauth = [
            'oauth_consumer_key'     => $this->ck,
            'oauth_nonce'            => bin2hex(random_bytes(16)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => (string) time(),
            'oauth_token'            => $this->at,
            'oauth_version'          => '1.0',
        ];

        $params = $oauth;
        // include query params if present
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            $params = array_merge($params, $query);
        }

        ksort($params);
        $base = strtoupper($method).'&'.rawurlencode($this->withoutQuery($url)).'&'.
            rawurlencode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
        $key  = rawurlencode($this->cs).'&'.rawurlencode($this->as);
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base, $key, true));

        return 'OAuth '.implode(', ', array_map(
                fn($k,$v) => rawurlencode($k).'="'.rawurlencode($v).'"',
                array_keys($oauth), $oauth
            ));
    }

    /**
     * OAuth 1.0a header for x-www-form-urlencoded POST (body params ARE signed).
     */
    private function oauth1HeaderForm(string $method, string $url, array $bodyParams): string
    {
        $oauth = [
            'oauth_consumer_key'     => $this->ck,
            'oauth_nonce'            => bin2hex(random_bytes(16)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => (string) time(),
            'oauth_token'            => $this->at,
            'oauth_version'          => '1.0',
        ];

        $params = array_merge($oauth, $bodyParams);
        // include query params if present
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
            $params = array_merge($params, $query);
        }

        ksort($params);
        $base = strtoupper($method).'&'.rawurlencode($this->withoutQuery($url)).'&'.
            rawurlencode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
        $key  = rawurlencode($this->cs).'&'.rawurlencode($this->as);
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base, $key, true));

        return 'OAuth '.implode(', ', array_map(
                fn($k,$v) => rawurlencode($k).'="'.rawurlencode($v).'"',
                array_keys($oauth), $oauth
            ));
    }

    private function withoutQuery(string $url): string
    {
        $u = parse_url($url);
        $scheme = $u['scheme'] ?? 'https';
        $host   = $u['host'] ?? '';
        $path   = $u['path'] ?? '';
        return "{$scheme}://{$host}{$path}";
    }
}

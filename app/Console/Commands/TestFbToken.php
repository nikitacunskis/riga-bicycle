<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TestFbToken extends Command
{
    protected $signature = 'meta:test-token
        {--token= : Access token to test (defaults to META_PAGE_TOKEN)}
        {--app-id= : App ID (defaults to META_APP_ID; optional)}
        {--app-secret= : App Secret (defaults to META_APP_SECRET; optional)}
        {--page-id= : Expected FB Page ID (defaults to META_FB_PAGE_ID)}
        {--show-tokens : Also print full page tokens from /me/accounts if a USER token}';

    protected $description = 'Debug/validate Facebook token. Works with USER or PAGE tokens, no jq required.';

    public function handle(): int
    {
        $cfg        = config('services.meta');
        $baseUri    = rtrim($cfg['base_uri'] ?? 'https://graph.facebook.com', '/');
        $version    = $cfg['version'] ?? 'v21.0';

        $token      = (string)($this->option('token') ?? env('META_PAGE_TOKEN'));
        $appId      = (string)($this->option('app-id') ?? env('META_APP_ID'));
        $appSecret  = (string)($this->option('app-secret') ?? env('META_APP_SECRET'));
        $expectedPg = (string)($this->option('page-id') ?? env('META_FB_PAGE_ID'));

        if (!$token) {
            $this->error('Missing token. Provide --token or set META_PAGE_TOKEN.');
            return self::INVALID;
        }

        // Optional: /debug_token if app secret provided
        if ($appId && $appSecret) {
            $this->line('<info>[1/4]</info> /debug_token …');
            $debugUrl = "{$baseUri}/{$version}/debug_token";
            $debugResp = Http::asForm()->timeout(20)->get($debugUrl, [
                'input_token'  => $token,
                'access_token' => "{$appId}|{$appSecret}",
            ]);

            if ($debugResp->ok()) {
                $data = $debugResp->json('data') ?? [];
                $this->table(['key','value'], [
                    ['is_valid', ($data['is_valid'] ?? false) ? 'true' : 'false'],
                    ['type', $data['type'] ?? 'UNKNOWN'],
                    ['app_id', $data['app_id'] ?? '—'],
                    ['user_id', $data['user_id'] ?? '—'],
                    ['scopes', implode(', ', (array)($data['scopes'] ?? []))],
                    ['expires_at (unix)', $data['expires_at'] ?? '—'],
                    ['data_access_expires_at (unix)', $data['data_access_expires_at'] ?? '—'],
                ]);
                if (!($data['is_valid'] ?? false)) {
                    $this->error('Token is NOT valid per /debug_token.');
                    return self::FAILURE;
                }
            } else {
                $this->warn("debug_token failed (HTTP {$debugResp->status()}); continuing.");
            }
        } else {
            $this->warn('[skip] /debug_token (no App Secret provided).');
        }

        // Always safe: /me with id,name only (works for USER and PAGE tokens)
        $this->line('<info>[2/4]</info> GET /me (id,name) …');
        $meUrl = "{$baseUri}/{$version}/me";
        $me = Http::timeout(20)->get($meUrl, [
            'fields' => 'id,name',
            'access_token' => $token,
        ]);
        if (!$me->ok()) {
            $this->error("GET /me failed: HTTP {$me->status()}");
            $this->line($me->body());
            return self::FAILURE;
        }
        $meId   = (string)($me->json('id') ?? '');
        $meName = (string)($me->json('name') ?? '');
        $this->table(['field','value'], [
            ['id', $meId],
            ['name', $meName],
        ]);

        // Try /me/accounts — if it returns data, token is USER and we can fetch Page tokens
        $this->line('<info>[3/4]</info> GET /me/accounts …');
        $accUrl = "{$baseUri}/{$version}/me/accounts";
        $acc = Http::timeout(20)->get($accUrl, [
            'access_token' => $token,
            'fields' => 'id,name,perms,access_token',
            'limit' => 100,
        ]);

        $rows = [];
        $isUserToken = false;
        if ($acc->ok()) {
            $pages = (array)($acc->json('data') ?? []);
            $isUserToken = count($pages) > 0;
            foreach ($pages as $page) {
                $pid = (string)($page['id'] ?? '');
                $pname = (string)($page['name'] ?? '');
                $perms = implode(', ', (array)($page['perms'] ?? []));
                $ptok = (string)($page['access_token'] ?? '');
                $masked = $this->option('show-tokens') ? $ptok : $this->maskToken($ptok);
                $rows[] = [$pid, $pname, $perms, $masked];
            }
            if ($rows) {
                $this->table(['page_id', 'name', 'perms', 'page_token'], $rows);
            }
        } else {
            $this->line("(/me/accounts not accessible; HTTP {$acc->status()})");
        }

        // Decide likely token type
        $type = $isUserToken ? 'USER' : 'PAGE';
        $this->info("Inferred token type: {$type}");

        // Final guidance
        $this->line('<info>[4/4]</info> Guidance:');
        if ($type === 'USER') {
            $this->warn('Use a PAGE token for posting. Pick the row for your Page:');
            if ($expectedPg) {
                $match = collect($rows)->first(fn ($r) => $r[0] === $expectedPg);
                if ($match) {
                    $this->info("Expected Page ID {$expectedPg} found. Use its page_token for META_PAGE_TOKEN.");
                } else {
                    $this->warn("Expected Page ID {$expectedPg} NOT found. Choose the correct page from the table.");
                }
            }
            $this->line('Set:');
            $this->line('  META_PAGE_TOKEN=<the selected row’s page_token>');
            $this->line('  META_FB_PAGE_ID=<the selected row’s page_id>');
        } else {
            if ($expectedPg && $meId && $meId !== $expectedPg) {
                $this->warn("Expected META_FB_PAGE_ID={$expectedPg}, but token is for id={$meId} ({$meName}). Update your .env.");
            } elseif ($expectedPg && $meId === $expectedPg) {
                $this->info('Expected Page ID matches the token.');
            } else {
                $this->line('Set META_FB_PAGE_ID=' . $meId . ' in your .env');
            }
            $this->info('This PAGE token should work for posting.');
        }

        return self::SUCCESS;
    }

    private function maskToken(string $token): string
    {
        if ($token === '') return '—';
        $len = Str::length($token);
        if ($len <= 10) return str_repeat('*', $len);
        return Str::substr($token, 0, 6) . str_repeat('*', max(0, $len - 10)) . Str::substr($token, -4);
    }
}

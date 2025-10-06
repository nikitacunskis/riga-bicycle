<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Social\{SocialPoster, PostData};

class SocialPostController extends Controller
{
    public function __invoke(Request $r, SocialPoster $poster)
    {
        $validated = $r->validate([
            'channel' => 'required|string|in:facebook,x,telegram,instagram',
            'text'    => 'nullable|string|max:1000',
            'link'    => 'nullable|url',
            'media'   => 'array',
            'media.*' => 'string', // path or URL; drivers handle download/resolve
            'chat_id' => 'nullable|string', // telegram override
            'dry_run' => 'sometimes|boolean',
        ]);

        $meta = [];
        if (($validated['channel'] === 'telegram') && !empty($validated['chat_id'])) {
            $meta['chat_id'] = $validated['chat_id'];
        }

        $data = new PostData(
            text:  (string)($validated['text'] ?? ''),
            link:  $validated['link'] ?? null,
            media: $validated['media'] ?? [],
            meta:  $meta
        );

        if (!empty($validated['dry_run'])) {
            return response()->json([
                'ok'      => true,
                'dry_run' => true,
                'payload' => [
                    'channel' => $validated['channel'],
                    'text'    => $data->text,
                    'link'    => $data->link,
                    'media'   => $data->media,
                    'meta'    => $data->meta,
                ],
            ]);
        }

        $res = $poster->post($validated['channel'], $data);

        return response()->json([
            'ok'       => $res->ok,
            'remoteId' => $res->remoteId,
            'raw'      => $res->raw,
            'error'    => $res->error,
        ], $res->ok ? 200 : 422);
    }
}

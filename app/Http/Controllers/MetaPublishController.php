<?php

namespace App\Http\Controllers;

use App\Services\MetaPublisherService;
use Illuminate\Http\Request;

class MetaPublishController extends Controller
{
    public function fbPhoto(Request $request, MetaPublisherService $svc)
    {
        $data = $request->validate([
            'image_url' => ['required','url'],
            'caption'   => ['nullable','string','max:63206'],
            'dry'       => ['sometimes','boolean'],
        ]);

        $fbId = $svc->publishToFacebookPhoto($data['image_url'], $data['caption'] ?? '', (bool)($data['dry'] ?? false));

        return response()->json(['post_id' => $fbId]);
    }

    public function fbText(Request $request, MetaPublisherService $svc)
    {
        $data = $request->validate([
            'message' => ['required','string','max:63206'],
            'link'    => ['nullable','url'],
            'dry'     => ['sometimes','boolean'],
        ]);

        $id = $svc->publishToFacebookFeed($data['message'], $data['link'] ?? null, (bool)($data['dry'] ?? false));
        return response()->json(['post_id' => $id]);
    }
}

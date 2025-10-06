<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Api;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    public function test()
    {
        dd('ssssss');
    }
    public function index()
    {
        $apis = Api::query()
            ->select([
                'id',
                'owner',
                'phone',
                'email',
                'tos_accepted',
                'privacy_accepted',
                'type',
                'reg_number',
                'valid_until',
                'created_at',
                'updated_at',
            ])
            ->orderByDesc('id')
            ->get();

        return Inertia::render('API/List', ['apis' => $apis]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'owner'            => ['required', 'string', 'max:255'],
            'type'             => ['nullable', 'in:personal,organisation'],
            'valid_until'      => ['nullable', 'date'],
            'phone'            => ['nullable', 'string', 'max:50'],
            'email'            => ['nullable', 'email', 'max:255'],
            'reg_number'       => ['nullable', 'string', 'max:255'],
            'key'              => ['nullable', 'string', 'max:255'],
            'tos_accepted'     => ['required', 'boolean'],
            'privacy_accepted' => ['required', 'boolean'],
            'purpose_of_use'   => ['nullable', 'string', 'max:255'],
        ]);

        $data['valid_until'] = isset($data['valid_until']) && $data['valid_until']
            ? Carbon::parse($data['valid_until'])
            : now()->addYear();

        if (empty($data['key'])) {
            $data['key'] = Str::random(64);
        }

        // If DB columns are VARCHARs:
        $data['tos_accepted']     = $data['tos_accepted'] ? '1' : '0';
        $data['privacy_accepted'] = $data['privacy_accepted'] ? '1' : '0';
        $data['purpose_of_use']   = isset($data['purpose_of_use']) ? $data['purpose_of_use'] : '';

        if($data['type'] != 'organisation') {
            $data['reg_number'] = $data['owner'] . '-' . Str::uuid();
            $data['org_contact'] = $data['email'];
        }

        $api = Api::query()->create($data); // IDE-friendly

        if ($request->wantsJson()) {
            return response()->json(['api' => $api], 201);
        }

        return Redirect::route('dashboard.apis.index');
    }

    public function destroy($id, Request $request)
    {
        Api::findOrFail($id)->delete();

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return Redirect::route('dashboard.apis.index');
    }

    public function request() {
        return Inertia::render('API/Request');
    }

    /**
     * FE posts here: POST /apis/request
     */
    public function requestStore(Request $request) : JsonResponse
    {
        $v = $request->validate([
            'type'            => ['required', 'in:private,org'],
            'name'            => ['required_if:type,private', 'nullable', 'string', 'max:255'],
            'orgName'         => ['required_if:type,org', 'nullable', 'string', 'max:255'],
            'regNumber'       => ['required_if:type,org', 'nullable', 'string', 'max:255'],
            'contactName'     => ['required_if:type,org', 'nullable', 'string', 'max:255'],
            'phone'           => ['required', 'string', 'max:50'],
            'email'           => ['required', 'email', 'max:255'],
            'purpose'         => ['required', 'string', 'min:20'],
            'acceptedTos'     => ['required', 'boolean'],
            'acceptedPrivacy' => ['required', 'boolean'],
        ]);

        $dbType = $v['type'] === 'private' ? 'personal' : 'organisation';

        $owner = $v['type'] === 'private' ? $v['name'] : $v['orgName'];

        $api = Api::create([
            'key'              => Str::random(64),
            'owner'            => $owner,
            'valid_until'      => now()->addYear(),
            'phone'            => $v['phone'],
            'email'            => $v['email'],
            'tos_accepted'     => $v['acceptedTos'] ? '1' : '0',         // schema = string
            'privacy_accepted' => $v['acceptedPrivacy'] ? '1' : '0',      // schema = string
            'type'             => $dbType,                                 // enum value
            'reg_number'       => $v['type'] === 'org' ? ($v['regNumber'] ?? null) : null,
        ]);

        // Email ops
        $lines = [
            'New API key issued.',
            'Key: ' . $api->key,
            'Type: ' . $api->type,
            'Owner: ' . $api->owner,
            'Email: ' . $api->email,
            'Phone: ' . $api->phone,
            'Valid until: ' . $api->valid_until->toDateTimeString(),
        ];

        if ($api->type === 'organisation') {
            $lines[] = 'Reg Number: ' . ($api->reg_number ?? '-');
            $lines[] = 'Contact Person: ' . ($v['contactName'] ?? '-');
        }

        $lines[] = '';
        $lines[] = 'Purpose:';
        $lines[] = $v['purpose'];

        Mail::raw(implode("\n", $lines), function ($m) use ($api) {
            $m->to('info@pilsetacilvekiem.lv')
                ->subject('API key issued: ' . $api->owner);
        });

        return response()->json([
            'ok'  => true,
            'api' => [
//                'id'          => $api->id,
//                'key'         => $api->key,
                'owner'       => $api->owner,
//                'type'        => $api->type,
//                'valid_until' => $api->valid_until->toISOString(),
                'email'       => $api->email,
//                'phone'       => $api->phone,
//                'reg_number'  => $api->reg_number,
            ],
        ], 201);
    }

    public function documentation()
    {
        return Inertia::render('API/Documentation');
    }
}

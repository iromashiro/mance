<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class VoiceProxyController extends Controller
{
    /**
     * Proxy token request to upstream to avoid mobile CORS/Tracker blocking.
     */
    public function token(Request $request): JsonResponse
    {
        $identity = $request->query('identity', optional($request->user())->name ?? 'Guest');

        try {
            $resp = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => $request->header('User-Agent', 'MANCE/VoiceProxy'),
                    'Accept'     => 'application/json',
                ])
                ->get('https://voice.deltabhumi.co.id/getToken', [
                    'identity' => $identity,
                ]);

            if (!$resp->ok()) {
                return response()->json([
                    'message' => 'Upstream error',
                    'status'  => $resp->status(),
                    'body'    => $this->safeJson($resp),
                ], $resp->status());
            }

            return response()->json($resp->json());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Proxy error: ' . $e->getMessage(),
            ], 502);
        }
    }

    /**
     * Proxy invite request to upstream to avoid mobile CORS/Tracker blocking.
     */
    public function invite(Request $request): JsonResponse
    {
        $room = $request->input('room');
        if (!$room) {
            return response()->json(['message' => 'room is required'], 422);
        }

        try {
            $resp = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => $request->header('User-Agent', 'MANCE/VoiceProxy'),
                    'Accept'     => 'application/json',
                ])
                ->post('https://voice.deltabhumi.co.id/invite', [
                    'room' => $room,
                ]);

            if (!$resp->ok()) {
                return response()->json([
                    'message' => 'Upstream error',
                    'status'  => $resp->status(),
                    'body'    => $this->safeJson($resp),
                ], $resp->status());
            }

            return response()->json($resp->json());
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Proxy error: ' . $e->getMessage(),
            ], 502);
        }
    }

    private function safeJson(\Illuminate\Http\Client\Response $resp)
    {
        try {
            return $resp->json();
        } catch (\Throwable $e) {
            return $resp->body();
        }
    }
}

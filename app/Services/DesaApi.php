<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DesaApi
{
    protected string $base;
    protected string $email;
    protected string $password;
    protected ?int $villageId;

    public function __construct()
    {
        $this->base = rtrim(config('services.desa.url', 'https://desa-api.muaraenimkab.go.id/'), '/') . '/';
        $this->email = (string) config('services.desa.email', '');
        $this->password = (string) config('services.desa.password', '');
        $this->villageId = config('services.desa.village_id') !== null
            ? (int) config('services.desa.village_id')
            : null;
    }

    public function token(): ?string
    {
        return Cache::remember('desa_api_token', now()->addMinutes(50), function () {
            try {
                $resp = Http::asJson()
                    ->acceptJson()
                    ->timeout(10)
                    ->post($this->base . 'api/v1/login', [
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);

                if ($resp->successful()) {
                    return data_get($resp->json(), 'data.token');
                }
            } catch (\Throwable $e) {
                // swallow error, treat as no token
            }

            return null;
        });
    }

    protected function client()
    {
        $client = Http::acceptJson()->timeout(15);
        $token = $this->token();
        return $token ? $client->withToken($token) : $client;
    }

    /**
     * Get all articles (optionally filtered by village_id if configured).
     *
     * @return array<object>
     */
    public function articles(): array
    {
        try {
            $resp = $this->client()->get($this->base . 'api/v1/article');
            if (! $resp->successful()) {
                throw new \RuntimeException('Article list failed: ' . $resp->status());
            }
            $items = (array) data_get($resp->json(), 'data', []);
        } catch (\Throwable $e) {
            $items = [];
        }

        // Filter by village_id if set, but fallback to all if result empty
        if ($this->villageId) {
            $filtered = array_values(array_filter($items, function ($a) {
                return (int) ($a['village_id'] ?? 0) === (int) $this->villageId;
            }));
            if (! empty($filtered)) {
                $items = $filtered;
            }
        }

        $mapped = array_map(fn($it) => $this->mapArticle((array) $it), $items);

        // Sort by published_at desc
        usort($mapped, function ($a, $b) {
            $ad = $a->published_at ?? Carbon::now()->subYears(50);
            $bd = $b->published_at ?? Carbon::now()->subYears(50);
            return $bd->timestamp <=> $ad->timestamp;
        });

        return $mapped;
    }

    /**
     * Get single article by id.
     */
    public function article(int|string $id): ?object
    {
        try {
            $resp = $this->client()->get($this->base . 'api/v1/article/' . $id);
            if (! $resp->successful()) {
                throw new \RuntimeException('Article show failed: ' . $resp->status());
            }
            $data = (array) data_get($resp->json(), 'data');
            if (! $data) {
                return null;
            }
            return $this->mapArticle($data);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Map remote payload to a simple object compatible with existing views.
     */
    protected function mapArticle(array $raw): object
    {
        $o = new \stdClass();
        $o->source = 'external';
        $o->id = $raw['id'] ?? null;
        $o->title = $raw['title'] ?? '-';
        $o->slug = $raw['slug'] ?? null;

        $content = (string) ($raw['content'] ?? '');
        $o->content = $content;
        $o->excerpt = $raw['description'] ?? Str::limit(strip_tags($content), 160);

        $thumb = $raw['thumbnail'] ?? null;
        $o->image_url = $thumb;          // frontend views use image_url
        $o->thumbnail_path = $thumb;      // some cards expect thumbnail_path

        $o->views = (int) ($raw['views'] ?? 0);
        $o->published_at = isset($raw['published_at']) ? Carbon::parse($raw['published_at']) : null;

        // keep shape similar to Eloquent relation used in views
        $o->author = (object) ['name' => 'Admin Muara Enim'];

        $o->category = $raw['category_id'] ?? null;
        $o->tags = null;
        $o->village_id = (int) ($raw['village_id'] ?? 0);

        return $o;
    }
}

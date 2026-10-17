<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(
            "setting.{$key}",
            function () use ($key, $default) {
                $setting = self::query()->where('key', $key)->first();

                if (! $setting) {
                    return $default;
                }

                return match ($setting->type) {
                    'boolean' => (bool) $setting->value,
                    'integer' => (int) $setting->value,
                    default => $setting->value,
                };
            }
        );
    }

    public static function set(string $key, mixed $value): void
    {
        $type = match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            default => 'string',
        };

        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? (string) (int) $value : (string) $value, 'type' => $type]
        );

        Cache::forget("setting.{$key}");
    }
}

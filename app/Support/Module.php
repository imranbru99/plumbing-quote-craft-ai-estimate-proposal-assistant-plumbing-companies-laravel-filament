<?php

namespace App\Support;

use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Module
{
    public static function enabled(string $key): bool
    {
        $config = config("modules.{$key}");
        if ($config === null) {
            return false;
        }

        $settings = static::settingsMap();

        if (array_key_exists($key, $settings) && $settings[$key] !== null) {
            return (bool) $settings[$key];
        }

        return (bool) ($config['enabled'] ?? false);
    }

    public static function all(): array
    {
        $modules = config('modules', []);
        $settings = static::settingsMap();
        $out = [];

        foreach ($modules as $key => $meta) {
            $enabled = array_key_exists($key, $settings) && $settings[$key] !== null
                ? (bool) $settings[$key]
                : (bool) ($meta['enabled'] ?? false);

            $out[$key] = array_merge($meta, [
                'key' => $key,
                'enabled' => $enabled,
            ]);
        }

        return $out;
    }

    public static function clearCache(): void
    {
        Cache::forget('module_settings_map');
        foreach (array_keys(config('modules', [])) as $key) {
            Cache::forget("module_setting_{$key}");
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected static function settingsMap(): array
    {
        try {
            return Cache::remember('module_settings_map', 60, function () {
                if (! Schema::hasTable('module_settings')) {
                    return [];
                }

                return ModuleSetting::query()->pluck('enabled', 'key')->all();
            });
        } catch (\Throwable) {
            return [];
        }
    }
}
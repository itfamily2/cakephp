<?php
declare(strict_types=1);

namespace Settings\Utility;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Cache\Cache;

/**
 * Enterprise Settings Registry
 * A robust backend utility to retrieve, update, and cache system settings.
 */
class SettingsRegistry
{
    use LocatorAwareTrait;

    /**
     * Get a setting by key, cached for high performance.
     */
    public function get(string $key, $default = null)
    {
        $cacheKey = "setting_{$key}";
        
        return Cache::remember($cacheKey, function () use ($key, $default) {
            $settingsTable = $this->fetchTable('Settings');
            $setting = $settingsTable->find()
                ->select(['value', 'type'])
                ->where(['key' => $key])
                ->first();
                
            if (!$setting) {
                return $default;
            }

            return $this->castValue($setting->value, $setting->type);
        }, 'default');
    }

    /**
     * Update or create a setting and invalidate the cache.
     */
    public function set(string $key, string $value, string $type = 'string'): bool
    {
        $settingsTable = $this->fetchTable('Settings');
        $setting = $settingsTable->find()->where(['key' => $key])->first();

        if (!$setting) {
            $setting = $settingsTable->newEmptyEntity();
            $setting->key = $key;
        }

        $setting->value = $value;
        $setting->type = $type;

        if ($settingsTable->save($setting)) {
            Cache::delete("setting_{$key}", 'default');
            return true;
        }
        return false;
    }

    private function castValue(string $value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int)$value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}

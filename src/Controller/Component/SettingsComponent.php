<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Phase 11 - SettingsComponent
 * 
 * Provides easy access to application settings stored in the database.
 */
class SettingsComponent extends Component
{
    /**
     * Get a setting value by key.
     *
     * @param string $key The setting key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    public function read(string $key, $default = null)
    {
        // First check Configure (cache)
        if (Configure::check('Settings.' . $key)) {
            return Configure::read('Settings.' . $key);
        }

        try {
            $settingsTable = TableRegistry::getTableLocator()->get('Settings');
            $setting = $settingsTable->find()->where(['key' => $key])->first();
            
            if ($setting) {
                // Cache it for the rest of the request
                Configure::write('Settings.' . $key, $setting->value);
                return $setting->value;
            }
        } catch (\Exception $e) {
            \Cake\Log\Log::warning('Settings table not accessible: ' . $e->getMessage());
        }

        return $default;
    }

    /**
     * Write a setting value to the database.
     *
     * @param string $key The setting key
     * @param string $value The setting value
     * @return bool
     */
    public function write(string $key, string $value): bool
    {
        try {
            $settingsTable = TableRegistry::getTableLocator()->get('Settings');
            $setting = $settingsTable->find()->where(['key' => $key])->first();
            
            if (!$setting) {
                $setting = $settingsTable->newEmptyEntity();
                $setting->key = $key;
            }
            
            $setting->value = $value;
            
            if ($settingsTable->save($setting)) {
                Configure::write('Settings.' . $key, $value);
                return true;
            }
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Failed to write setting: ' . $e->getMessage());
        }
        
        return false;
    }
}

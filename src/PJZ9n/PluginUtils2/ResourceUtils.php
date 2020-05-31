<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of PluginUtils2.
 *
 * PluginUtils2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PluginUtils2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PluginUtils2. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PJZ9n\PluginUtils2;

use pocketmine\plugin\Plugin;

class ResourceUtils
{
    /**
     * Update config.
     *
     * @param Plugin $plugin
     *
     * @return int Count of added values.
     */
    public static function updateConfig(Plugin $plugin): int
    {
        $fp = $plugin->getResource("config.yml");
        $configYaml = "";
        while (!feof($fp)) {
            $configYaml .= fgets($fp);
        }
        fclose($fp);
        $beforeCount = count($plugin->getConfig()->getAll(), COUNT_RECURSIVE);
        $parsedYaml = yaml_parse($configYaml);
        if (!is_array($parsedYaml)) {
            $parsedYaml = [];
        }
        $plugin->getConfig()->setDefaults($parsedYaml);//replace
        $afterCount = count($plugin->getConfig()->getAll(), COUNT_RECURSIVE);
        $plugin->saveConfig();
        return $afterCount - $beforeCount;
    }
    
    /**
     * Save all language files.
     *
     * @param Plugin $plugin
     * @param string $localeDir relative path of resource. Ex: locale/
     * @param bool $overWrite
     *
     * @return int Count of updated files.
     */
    public static function saveLanguageFiles(Plugin $plugin, string $localeDir = "locale/", bool $overWrite = true): int
    {
        $count = 0;
        foreach ($plugin->getResources() as $path => $resource) {
            if (strpos($path, $localeDir) === 0 && $resource->getExtension() === "ini") {
                if ($plugin->saveResource($path, $overWrite)) {
                    $count++;
                }
            }
        }
        return $count;
    }
}

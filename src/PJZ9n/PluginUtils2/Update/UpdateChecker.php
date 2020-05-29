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

namespace PJZ9n\PluginUtils2\Update;

use PJZ9n\PluginUtils2\Tasks\GetLatestReleaseTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class UpdateChecker
{
    /**
     * @param string $currentVersion
     * @param string $url Ex: https://api.github.com/repos/author/repo/releases/latest
     * @param callable $callback function (?UpdateDescription $description): void {}
     * Returns UpdateDescription if a new version exists.
     * Returns null if it could not be obtained, or if there is no new version.
     */
    public static function check(string $currentVersion, string $url, string $userAgent, callable $callback): void
    {
        Utils::validateCallableSignature(function (?UpdateDescription $description): void {
        }, $callback);
        $callable = function (?array $releaseData) use ($currentVersion, $callback): void {
            if (!isset($releaseData["html_url"], $releaseData["tag_name"])) {
                $callback(null);
                return;
            }
            $htmlUrl = $releaseData["html_url"];
            $tagName = $releaseData["tag_name"];
            $compare = version_compare($tagName, $currentVersion);
            if ($compare !== 1) {
                $callback(null);
                return;
            }
            $callback(new UpdateDescription(
                $htmlUrl,
                $tagName
            ));
        };
        Server::getInstance()->getAsyncPool()->submitTask(new GetLatestReleaseTask($url, $userAgent, $callable));
    }
}

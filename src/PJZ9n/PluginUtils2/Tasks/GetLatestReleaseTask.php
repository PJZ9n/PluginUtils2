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

namespace PJZ9n\PluginUtils2\Tasks;

use PJZ9n\PluginUtils2\Utils\Json;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class GetLatestReleaseTask extends AsyncTask
{
    /** @var string */
    private $url;
    
    /** @var string */
    private $userAgent;
    
    /** @var callable */
    private $callback;
    
    /**
     * @param string $url Ex: https://api.github.com/repos/author/repo/releases/latest
     * @param string $userAgent
     * @param callable $callback function (?array $releaseData): void {}
     * When you get the result, call $callable.
     * Usually returns the current latest release data.
     * If acquisition of the version fails, null is returned.
     */
    public function __construct(string $url, string $userAgent, callable $callback)
    {
        $this->url = $url;
        $this->userAgent = $userAgent;
        Utils::validateCallableSignature(function (?array $releaseData): void {
        }, $callback);
        $this->callback = $callback;
    }
    
    public function onRun()
    {
        $ch = curl_init($this->url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->userAgent,
        ]);
        $this->setResult(curl_exec($ch));
        curl_close($ch);
    }
    
    public function onCompletion(Server $server)
    {
        $result = $this->getResult();
        if (!is_string($result)) {
            ($this->callback)(null);
            return;
        }
        if (!Json::isValid($result)) {
            ($this->callback)(null);
            return;
        }
        ($this->callback)(json_decode($result, true));
    }
}

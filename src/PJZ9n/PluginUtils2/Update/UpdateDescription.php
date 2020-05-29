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

class UpdateDescription
{
    /** @var string */
    private $htmlUrl;
    
    /** @var string */
    private $tagName;
    
    /**
     * @param string $htmlUrl Ex: https://github.com/author/repo/releases/tag/tag_name
     * @param string $tagName Ex: 1.0.0
     */
    public function __construct(string $htmlUrl, string $tagName)
    {
        $this->htmlUrl = $htmlUrl;
        $this->tagName = $tagName;
    }
    
    /**
     * @return string
     */
    public function getHtmlUrl(): string
    {
        return $this->htmlUrl;
    }
    
    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }
}

# PluginUtils2
Utility library for PMMP plugins.

```php
<?php

declare(strict_types=1);

namespace Example;

use PJZ9n\PluginUtils2\Update\UpdateChecker;
use PJZ9n\PluginUtils2\Update\UpdateDescription;
use pocketmine\plugin\PluginBase;
use const pocketmine\NAME;
use const pocketmine\VERSION;

class Main extends PluginBase
{
    public function onEnable()
    {
        $logger = $this->getLogger();
        UpdateChecker::check(
            $this->getDescription()->getVersion(),
            "https://api.github.com/repos/author/repo/releases/latest",
            NAME . " " . VERSION,//Ex: PocketMine-MP 3.0.0
            function (?UpdateDescription $description) use ($logger): void {
                if ($description === null) return;//Update is not found.
                $url = $description->getHtmlUrl();
                $version = $description->getTagName();
                $logger->info("Update found! version: {$version}, url: {$url}");
            }
        );
    }
}
```

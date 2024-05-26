<?php

namespace App;

use Pimcore\Extension\Bundle\Installer\SettingsStoreAwareInstaller;

class Installer extends SettingsStoreAwareInstaller
{
    public function getLastMigrationVersionClassName(): ?string
    {
        return null;
    }

    public function install(): void
    {
        $this->markInstalled();
    }

    public function uninstall(): void
    {
        $this->markUninstalled();
    }
}

<?php

namespace App;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class AppBundle extends AbstractPimcoreBundle
{
    public function getInstaller(): ?Installer
    {
        return $this->container->get(Installer::class);
    }
}

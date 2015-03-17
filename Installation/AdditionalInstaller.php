<?php

namespace Innova\MediaResourceBundle\Installation;

use Claroline\InstalationBundle\Additional\AdditinalInstaller as BaseInstaller;

class AdditionalInsaller extends BaseInstaller
{
    private $logger;

    public function __construct()
    {
        $self = $this;
        $this->logger = function ($message) use ($self) {
            $self->log($message);
        }
    }

    public function preUpdate($currentVersion, $targetVersion)
    {
    }

    public function postUpdate($currentVersion, $targetVersion)
    {
    }
}

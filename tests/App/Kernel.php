<?php

namespace Rlb\MakerExtraBundle\Tests\App;

use Symfony\Component\HttpKernel\Kernel as SfKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends SfKernel
{
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MakerBundle\MakerBundle(),
            new \Rlb\MakerExtraBundle\MakerExtraBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yaml');
    }
}

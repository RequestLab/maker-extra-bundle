<?php

namespace Rlb\MakerExtraBundle\DependencyInjection;

use Rlb\MakerExtraBundle\DependencyInjection\CompilerPass\MakeExtraCommandRegistrationPass;
use Rlb\MakerExtraBundle\MakerExtraInterface;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MakerExtraExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('makers.xml');

        /* $configuration = $this->getConfiguration($configs, $container); */
        /* $config = $this->processConfiguration($configuration, $configs); */

        /* $rootNamespace = trim($config['root_namespace'], '\\'); */

        /* $makeCommandDefinition = $container->getDefinition('maker.generator'); */
        /* $makeCommandDefinition->replaceArgument(1, $rootNamespace); */

        /* $doctrineHelperDefinition = $container->getDefinition('maker.doctrine_helper'); */
        /* $doctrineHelperDefinition->replaceArgument(0, $rootNamespace.'\\Entity'); */

        $container->registerForAutoconfiguration(MakerExtraInterface::class)
            ->addTag(MakeExtraCommandRegistrationPass::MAKER_TAG);
    }
}

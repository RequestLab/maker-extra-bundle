<?php

namespace Rlb\MakerExtraBundle\DependencyInjection\CompilerPass;

use Symfony\Bundle\MakerBundle\Command\MakerCommand;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class MakeExtraCommandRegistrationPass implements CompilerPassInterface
{
    const MAKER_TAG = 'maker.extra.command';

    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds(self::MAKER_TAG) as $id => $tags) {
            $def = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($def->getClass());

            if (!is_subclass_of($class, MakerInterface::class)) {
                throw new InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, MakerInterface::class));
            }

            $commandDefinition = new ChildDefinition('maker.auto_command.abstract');
            $commandDefinition->setClass(MakerCommand::class);
            $commandDefinition->replaceArgument(0, new Reference($id));
            $commandDefinition->addTag('console.command', ['command' => $class::getCommandName()]);

            $container->setDefinition(sprintf('maker.auto_command.%s', Str::asTwigVariable($class::getCommandName())), $commandDefinition);
        }
    }
}

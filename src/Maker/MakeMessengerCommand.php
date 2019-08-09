<?php

namespace Rlb\MakerExtraBundle\Maker;

use Doctrine\Common\Annotations\Annotation;
use Rlb\MakerExtraBundle\ExtraGenerator;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeMessengerCommand extends AbstractMaker
{
    /**
     * @var ExtraGenerator
     */
    private $extraGenerator;

    public function __construct(ExtraGenerator $extraGenerator)
    {
        $this->extraGenerator = $extraGenerator;
    }

    public static function getCommandName(): string
    {
        return 'make:messenger:command';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new command and commandHandler class')
            ->addArgument('messenger-command-class', InputArgument::OPTIONAL, sprintf('Choose a name for your messenger command class (e.g. <fg=yellow>%sCommand</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('no-template', null, InputOption::VALUE_NONE, 'Use this option to disable template generation')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeMessengerCommand.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $messengerCommandClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('messenger-command-class'),
            'Application\\Command\\',
            'Command'
        );

        $this->extraGenerator->generateClass(
            $messengerCommandClassNameDetails->getFullName(),
            'messenger/command/Command.tpl.php',
            [
            ]
        );

        $this->extraGenerator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your new serializer encoder class and start customizing it.',
            'Find the documentation at <fg=yellow>http://symfony.com/doc/current/serializer/custom_encoders.html</>',
        ]);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }
}

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
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

final class MakeMessengerCommand extends AbstractMaker
{
    /**
     * @var ExtraGenerator
     */
    private $extraGenerator;

    /**
     * @var string
     */
    private $domainName;

    /**
     * @var string
     */
    private $commandName;

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
            ->addArgument('messenger-command-domain', InputArgument::OPTIONAL, 'In which domain ? (leave empty for root level)')
            ->addOption('no-template', null, InputOption::VALUE_NONE, 'Use this option to disable template generation')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeMessengerCommand.txt'))
        ;

        $inputConf->setArgumentAsNonInteractive('messenger-command-domain');
    }


    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (null === $input->getArgument('messenger-command-domain')) {
            $directories = glob($this->extraGenerator->getRootApp().'/*', GLOB_ONLYDIR);
            $directories = array_map('basename', $directories);

            $argument = $command->getDefinition()->getArgument('messenger-command-domain');

            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($directories);

            $input->setArgument('messenger-command-domain', $io->askQuestion($question));
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $this->domainName = $input->getArgument('messenger-command-domain') ? ucfirst($input->getArgument('messenger-command-domain')).'\\' : null;
        $this->commandName = $input->getArgument('messenger-command-class');

        $messengerCommandClassNameDetails = $this->generateCommandFileClass($generator);

        $this->generateHandlerFileClass($generator, [
            'commandName' => $messengerCommandClassNameDetails->getShortName(),
            'commandNameWithNamespace' => $messengerCommandClassNameDetails->getFullName(),
        ]);

        $this->extraGenerator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your new command / handler classes and start customizing them.',
        ]);
    }

    public function generateCommandFileClass(Generator $generator)
    {
        $messengerCommandClassNameDetails = $generator->createClassNameDetails(
            $this->commandName,
            $this->domainName.'Application\\Command\\',
            'Command'
        );

        $this->extraGenerator->generateClass(
            $messengerCommandClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/messenger/command/Command.tpl.php',
            []
        );

        return $messengerCommandClassNameDetails;
    }

    public function generateHandlerFileClass(Generator $generator, array $vars = [])
    {
        $messengerHandlerClassNameDetails = $generator->createClassNameDetails(
            $this->commandName,
            $this->domainName.'Application\\Command\\Handler\\',
            'Handler'
        );

        $this->extraGenerator->generateClass(
            $messengerHandlerClassNameDetails->getFullName(),
            __DIR__.'/../Resources/skeleton/messenger/command/handler/Handler.tpl.php',
            $vars
        );
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }
}

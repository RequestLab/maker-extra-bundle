<?php

namespace Rlb\MakerExtraBundle\Maker;

use Doctrine\Common\Annotations\Annotation;
use Rlb\MakerExtraBundle\ExtraGenerator;
use Rlb\MakerExtraBundle\MakerExtraInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class MakeDomain extends AbstractMaker
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
        return 'make:domain';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new domain folder')
            ->addArgument('domain-name', InputArgument::OPTIONAL, sprintf('Choose a name for your domain name (e.g. <fg=yellow>%sdomain</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('no-template', null, InputOption::VALUE_NONE, 'Use this option to disable template generation')
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeDomain.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $domainName = $input->getArgument('domain-name');

        $this->extraGenerator->generateFolder(ucfirst($domainName), 'domain');

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new domain and add some code!');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }
}

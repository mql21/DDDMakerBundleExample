<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\MissingDirectoriesGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMissingDirectoriesConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:generate-missing-directories';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private MissingDirectoriesGenerator $missingDirectoriesGenerator;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        $this->missingDirectoriesGenerator = new MissingDirectoriesGenerator();
        $this
            ->setDescription('Creates a value object in the Domain layer.')
            ->addArgument(
                'boundedContext',
                InputArgument::REQUIRED,
                'The name of the bounded context where Command will be saved into.'
            )
            ->addArgument(
                'module',
                InputArgument::REQUIRED,
                'The name of the module inside the bounded context where Command will be saved into.'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boundedContextName = $input->getArgument('boundedContext');
        $moduleName = $input->getArgument('module');
        
        $this->boundedContextModuleLocator->checkIfBoundedContextModuleExists($boundedContextName, $moduleName);
        
        $this->missingDirectoriesGenerator->generate($boundedContextName, $moduleName);
        
        $output->writeln("<info> Missing directories for module {$moduleName} of {$boundedContextName} bounded context have been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
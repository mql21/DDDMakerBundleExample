<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\UseCaseGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeUseCaseConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:application:make:use-case';
    
    private UseCaseGenerator $useCaseGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        $this->useCaseGenerator = new UseCaseGenerator();
        
        $this
            ->setDescription('Creates a use case (application service) in the Application layer.')
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
        
        // Ask for value object name and create it
        $useCaseNameQuestion = new Question("<info> What should the use case be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $useCaseName = $questionHelper->ask($input, $output, $useCaseNameQuestion);
        
        $this->useCaseGenerator->generate($boundedContextName, $moduleName, $useCaseName);
        
        $output->writeln("<info> Use case {$useCaseName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
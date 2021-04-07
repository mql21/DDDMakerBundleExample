<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Finder\CommandFinder;
use Mql21\DDDMakerBundle\Finder\UseCaseFinder;
use Mql21\DDDMakerBundle\Generator\CommandHandlerGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeCommandHandlerConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:command-handler';
    
    private CommandHandlerGenerator $commandHandlerGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private CommandFinder $commandFinder;
    private UseCaseFinder $useCaseFinder;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        
        $this->commandHandlerGenerator = new CommandHandlerGenerator();
        
        $this->commandFinder = new CommandFinder();
        $this->useCaseFinder = new UseCaseFinder();
    
        $this
            ->setDescription('Creates a command handler in the Application layer.')
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
        
        $commandNameQuestion = new Question("<info> What command should the command handler listen to?</info>\n > ");
        $commandNameQuestion->setAutocompleterValues(
            $this->commandFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
    
        $commandName = $questionHelper->ask($input, $output, $commandNameQuestion);
    
        $useCaseQuestion = new Question("<info> What use case should the event handler execute?</info>\n > ");
        $useCaseQuestion->setAutocompleterValues(
            $this->useCaseFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
    
        // Following var is not being used now, will be used in the near future bc use case needs to be instantiated
        // inside the handler
        $useCaseName = $questionHelper->ask($input, $output, $useCaseQuestion);
        
        $this->commandHandlerGenerator->generate($boundedContextName, $moduleName, $commandName);
    
        $output->writeln("<info> Command handler {$commandName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Finder\CommandFinder;
use Mql21\DDDMakerBundle\Generator\CommandGenerator;
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
    
    private CommandGenerator $commandGenerator;
    private CommandHandlerGenerator $commandHandlerGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private CommandFinder $commandFinder;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        
        $this->commandGenerator = new CommandGenerator();
        $this->commandHandlerGenerator = new CommandHandlerGenerator();
        
        $this->commandFinder = new CommandFinder();
        
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
        
        $availableCommands = $this->commandFinder->findIn($boundedContextName, $moduleName);
        
        $commandHandlerNameQuestion = new Question("<info> What should the command handler be called?</info>\n > ");
        $commandHandlerNameQuestion->setAutocompleterValues($availableCommands);
        $questionHelper = $this->getHelper('question');
    
        $commandHandlerName = $questionHelper->ask($input, $output, $commandHandlerNameQuestion);
        
        $this->commandHandlerGenerator->generate($boundedContextName, $moduleName, $commandHandlerName);
    
        $output->writeln("<info> Command handler {$commandHandlerName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
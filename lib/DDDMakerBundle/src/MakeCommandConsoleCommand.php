<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\CommandGenerator;
use Mql21\DDDMakerBundle\Generator\CommandHandlerGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeCommandConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:command';
    
    private CommandGenerator $commandGenerator;
    private CommandHandlerGenerator $commandHandlerGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        
        $this->commandGenerator = new CommandGenerator();
        $this->commandHandlerGenerator = new CommandHandlerGenerator();
        
        $this
            ->setDescription('Creates a command in the Application layer.')
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
        
        // Ask for command name and create it
        $commandNameQuestion = new Question("<info> What should the command be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $commandName = $questionHelper->ask($input, $output, $commandNameQuestion);
        
        $this->commandGenerator->generate($boundedContextName, $moduleName, $commandName);
    
        $output->writeln("<info> Command {$commandName} has been successfully created! </info>\n\n");
    
        // Ask if command handler should be created and create if so
        $createCommandHandlerQuestion = new ConfirmationQuestion(
            "<info> Do you wish to create command handler now (y/n)? (You can create it later with ddd:cqrs:make:command-handler)</info>\n > ",
            false,
            '/^(y|s)/i'
        );
    
        $createCommandHandler = $questionHelper->ask($input, $output, $createCommandHandlerQuestion);
        if (!$createCommandHandler) {
            return Command::SUCCESS;
        }
    
        $this->commandHandlerGenerator->generate($boundedContextName, $moduleName, $commandName);
        $output->writeln("<info> Command handler for {$commandName} command has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
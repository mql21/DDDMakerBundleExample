<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\CommandGenerator;
use Mql21\DDDMakerBundle\Generator\CommandHandlerGenerator;
use Mql21\DDDMakerBundle\Generator\DomainEventGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Question\DTOAttributeQuestioner;
use Mql21\DDDMakerBundle\Question\DTODataResponse;
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
    private DTOAttributeQuestioner $attributeQuestioner;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
    
        $this->attributeQuestioner = new DTOAttributeQuestioner();
        
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
        
        $output->writeln("<info>\n Now tell me what attributes should the event have! </info>\n\n");
        
        $this->commandGenerator = new CommandGenerator(
            $this->attributeQuestioner->ask($input, $output, $questionHelper)
        );
        
        $this->commandGenerator->generate($boundedContextName, $moduleName, $commandName);
    
        $output->writeln("<info> Command {$commandName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
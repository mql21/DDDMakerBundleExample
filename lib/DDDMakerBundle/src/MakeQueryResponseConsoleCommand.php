<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\CommandGenerator;
use Mql21\DDDMakerBundle\Generator\CommandHandlerGenerator;
use Mql21\DDDMakerBundle\Generator\QueryResponseGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeQueryResponseConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:response';
    
    private QueryResponseGenerator $queryResponseGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        $this->queryResponseGenerator = new QueryResponseGenerator();
        
        $this
            ->setDescription('Creates a query response in the Application layer.')
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
        
        // Ask for response name and create it
        $responseNameQuestion = new Question("<info> What should the response be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $responseName = $questionHelper->ask($input, $output, $responseNameQuestion);
        
        $this->queryResponseGenerator->generate($boundedContextName, $moduleName, $responseName);
        
        $output->writeln("<info> Response {$responseName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
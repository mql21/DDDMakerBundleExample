<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\QueryGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Question\DTOAttributeQuestioner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeQueryConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:query';
    
    private QueryGenerator $queryGenerator;
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
            ->setDescription('Creates a query in the Application layer.')
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
        
        // Ask for query name and create it
        $queryNameQuestion = new Question("<info> What should the query be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $queryName = $questionHelper->ask($input, $output, $queryNameQuestion);
    
        $this->queryGenerator = new QueryGenerator(
            $this->attributeQuestioner->ask($input, $output, $questionHelper)
        );
        
        $this->queryGenerator->generate($boundedContextName, $moduleName, $queryName);
    
        $output->writeln("<info> Query {$queryName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
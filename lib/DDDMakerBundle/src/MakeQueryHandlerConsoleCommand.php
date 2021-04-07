<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Finder\QueryFinder;
use Mql21\DDDMakerBundle\Generator\QueryHandlerGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeQueryHandlerConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:query-handler';
    
    private QueryHandlerGenerator $queryHandlerGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private QueryFinder $queryFinder;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        
        $this->queryHandlerGenerator = new QueryHandlerGenerator();
        
        $this->queryFinder = new QueryFinder();
        
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
        
        $queryHandlerNameQuestion = new Question("<info> What should the query handler be called?</info>\n > ");
        $queryHandlerNameQuestion->setAutocompleterValues(
            $this->queryFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
        
        $queryHandlerName = $questionHelper->ask($input, $output, $queryHandlerNameQuestion);
        
        $this->queryHandlerGenerator->generate($boundedContextName, $moduleName, $queryHandlerName);
        
        $output->writeln("<info> Query handler {$queryHandlerName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
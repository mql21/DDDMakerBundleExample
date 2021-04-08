<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Finder\QueryFinder;
use Mql21\DDDMakerBundle\Finder\ResponseFinder;
use Mql21\DDDMakerBundle\Finder\UseCaseFinder;
use Mql21\DDDMakerBundle\Generator\QueryHandlerGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Mql21\DDDMakerBundle\Response\UseCaseResponse;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeQueryHandlerConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:query-handler';
    
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    private QueryFinder $queryFinder;
    private UseCaseFinder $useCaseFinder;
    private ResponseFinder $responseFinder;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        $this->queryFinder = new QueryFinder();
        $this->useCaseFinder = new UseCaseFinder();
        $this->responseFinder = new ResponseFinder();
    
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
    
        $queryNameQuestion = new Question("<info> What query should the query handler listen to?</info>\n > ");
        $queryNameQuestion->setAutocompleterValues(
            $this->queryFinder->findIn($boundedContextName, $moduleName)
        );
        $questionHelper = $this->getHelper('question');
    
        $queryName = $questionHelper->ask($input, $output, $queryNameQuestion);
    
        $useCaseQuestion = new Question("<info> What use case should the query handler execute?</info>\n > ");
        $useCaseQuestion->setAutocompleterValues(
            $this->useCaseFinder->findIn($boundedContextName, $moduleName)
        );
        $useCaseNameResponse = new UseCaseResponse($questionHelper->ask($input, $output, $useCaseQuestion));
    
        $responseClassNameQuestion = new Question("<info> What response object should the query handler return?</info>\n > ");
        $responseClassNameQuestion->setAutocompleterValues(
            $this->responseFinder->findIn($boundedContextName, $moduleName)
        );
        $responseClassName = $questionHelper->ask($input, $output, $responseClassNameQuestion);
    
        $queryHandlerGenerator = new QueryHandlerGenerator($useCaseNameResponse, $responseClassName);
        $queryHandlerGenerator->generate($boundedContextName, $moduleName, $queryName);
    
        $output->writeln("<info> Query handler {$queryName} has been successfully created! </info>\n\n");
    
        return Command::SUCCESS;
    }
}
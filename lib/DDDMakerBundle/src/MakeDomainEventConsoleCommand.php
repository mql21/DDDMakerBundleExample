<?php

namespace Mql21\DDDMakerBundle;

use Mql21\DDDMakerBundle\Generator\DomainEventGenerator;
use Mql21\DDDMakerBundle\Locator\BoundedContextModuleLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class MakeDomainEventConsoleCommand extends Command
{
    protected static $defaultName = 'ddd:domain:make:event';
    
    private DomainEventGenerator $domainEventGenerator;
    private BoundedContextModuleLocator $boundedContextModuleLocator;
    
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->boundedContextModuleLocator = new BoundedContextModuleLocator();
        $this->domainEventGenerator = new DomainEventGenerator();
        
        $this
            ->setDescription('Creates a domain event in the Domain layer.')
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
        
        // Ask for event name and create it
        $eventNameQuestion = new Question("<info> What should the event be called?</info>\n > ");
        $questionHelper = $this->getHelper('question');
        $eventName = $questionHelper->ask($input, $output, $eventNameQuestion);
        
        $this->domainEventGenerator->generate($boundedContextName, $moduleName, $eventName);
        
        $output->writeln("<info> Event {$eventName} has been successfully created! </info>\n\n");
        
        return Command::SUCCESS;
    }
}
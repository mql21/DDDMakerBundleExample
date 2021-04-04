<?php


namespace Mql21\DDDMakerBundle\Locator;


use Mql21\DDDMakerBundle\Exception\ElementNotFoundException;
use Mql21\DDDMakerBundle\Finder\BoundedContextFinder;
use Mql21\DDDMakerBundle\Finder\ModuleFinder;
use Symfony\Component\Console\Command\Command;

class BoundedContextModuleLocator
{
    private BoundedContextLocator $boundedContextLocator;
    private ModuleLocator $moduleLocator;
    private BoundedContextFinder $boundedContextFinder;
    private ModuleFinder $moduleFinder;
    
    public function __construct()
    {
        $this->boundedContextLocator = new BoundedContextLocator();
        $this->moduleLocator = new ModuleLocator();
        $this->boundedContextFinder = new BoundedContextFinder();
        $this->moduleFinder = new ModuleFinder();
    }
    
    public function checkIfBoundedContextModuleExists(string $boundedContextName, string $moduleName)
    {
        if (!$this->boundedContextLocator->exists($boundedContextName)) {
            $this->displayBoundedContextNotFoundError($boundedContextName);
        }
    
        if (!$this->moduleLocator->exists($boundedContextName, $moduleName)) {
            $this->displayModuleNotFoundError($moduleName, $boundedContextName);
        }
    }
    
    
    protected function displayBoundedContextNotFoundError(string $boundedContextName): void
    {
        $availableBoundedContexts = implode(', ', $this->boundedContextFinder->find());
        
        throw new ElementNotFoundException("Bounded context {$boundedContextName} does not exist. Available bounded contexts: {$availableBoundedContexts}");
    }
    
    protected function displayModuleNotFoundError(string $moduleName, string $boundedContextName): void
    {
        $availableModules = implode(', ', $this->moduleFinder->findIn($boundedContextName));
        
        throw new ElementNotFoundException("Module {$moduleName} does not exist in bounded context {$boundedContextName}. Available modules: {$availableModules}");
    }
}
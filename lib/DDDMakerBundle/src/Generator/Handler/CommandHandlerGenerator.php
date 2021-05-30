<?php

namespace Mql21\DDDMakerBundle\Generator\Handler;

use Mql21\DDDMakerBundle\Exception\DirectoryNotFoundException;
use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\ValueObject\AttributeName;
use Mql21\DDDMakerBundle\ValueObject\ClassName;
use Mql21\DDDMakerBundle\ValueObject\ClassNamespace;
use Mql21\DDDMakerBundle\ValueObject\HandlerClass;

class CommandHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    public function generate(string $boundedContextName, string $moduleName, string $handlerName): void
    {
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($handlerName)
            ->ofDDDElementType($this->type())
            ->build();
        
        if (file_exists($dddClassBuilder->elementFullPath())) {
            ElementAlreadyExistsException::raise($handlerName, $boundedContextName, $moduleName);
        }
        
        $useCaseNamespace = $this->configManager->namespaceFor($boundedContextName, $moduleName, 'use-case');
        $classToHandleSuffix = $this->configManager->classSuffixFor($this->handles());
        
        $classToHandleNamespace = $this->configManager->namespaceFor($boundedContextName, $moduleName, $this->handles());
        
        if (!file_exists(dirname($dddClassBuilder->elementFullPath()))) {
            DirectoryNotFoundException::raise($dddClassBuilder->elementFullPath());
        }
        
        $handlerClass = new HandlerClass(
            new ClassNamespace($dddClassBuilder->namespace()),
            new ClassName($dddClassBuilder->elementClassName()),
            new ClassNamespace($dddClassBuilder->interfaceToImplementNamespace()),
            new ClassNamespace($dddClassBuilder->classToExtendNamespace()),
            new ClassNamespace("{$classToHandleNamespace}\\{$handlerName}{$classToHandleSuffix}"),
            new ClassName("{$handlerName}{$classToHandleSuffix}"),
            new AttributeName($this->handles()),
            new ClassNamespace("{$useCaseNamespace}\\{$this->useCaseResponse->useCase()}"),
            new ClassName($this->useCaseResponse->useCase()),
            new ClassNamespace(null)
        );
        
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render($handlerClass)
        );
    }
    
    public function type(): string
    {
        return 'command-handler';
    }
    
    public function handles(): string
    {
        return 'command';
    }
}
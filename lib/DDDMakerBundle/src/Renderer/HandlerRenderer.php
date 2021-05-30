<?php

namespace Mql21\DDDMakerBundle\Renderer;

use Mql21\DDDMakerBundle\ValueObject\DDDElement;
use Mql21\DDDMakerBundle\ValueObject\HandlerClass;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;

class HandlerRenderer implements DDDElementRenderer
{
    public function render(DDDElement $handlerClass): string
    {
        $namespace = new PhpNamespace($handlerClass->classNamespace()->namespace());
        $class = $namespace->addClass($handlerClass->className()->name());
        $class->setFinal();
    
        if (!empty($handlerClass->interfaceNamespace()->namespace())) {
            $namespace->addUse($handlerClass->interfaceNamespace()->namespace());
            $class->addImplement($handlerClass->interfaceNamespace()->namespace());
        }
    
        if (!empty($handlerClass->parentClassNamespace()->namespace())) {
            $namespace->addUse($handlerClass->parentClassNamespace()->namespace());
            $class->addExtend($handlerClass->parentClassNamespace()->namespace());
        }
    
        if (!empty($handlerClass->responseClassNamespace()->namespace())) {
            $namespace->addUse($handlerClass->responseClassNamespace()->namespace());
        }
    
        $namespace->addUse($handlerClass->classToHandleNamespace()->namespace());
        $namespace->addUse($handlerClass->useCaseNamespace()->namespace());
    
        $class
            ->addProperty("useCase")
            ->setType($handlerClass->useCaseNamespace()->namespace());
    
        $this->addConstructorMethod($class, $handlerClass);
    
        $this->addInvokeMethod($class, $handlerClass);
    
        return $this->renderNamespace($namespace);
    }
    
    private function renderNamespace(PhpNamespace $namespace): string
    {
        $printer = new PsrPrinter();
        return "<?php\n\ndeclare(strict_types=1);\n\n{$printer->printNamespace($namespace)}";
    }
    
    private function addConstructorMethod(ClassType $class, HandlerClass $handlerClass): void
    {
        $constructMethod = $class->addMethod("__construct");
        $constructMethod
            ->addParameter("useCase")
            ->setType($handlerClass->useCaseNamespace()->namespace());
        $constructMethod->setBody("\$this->useCase = \$useCase;");
    }
    
    private function addInvokeMethod(ClassType $class, DDDElement $handlerClass): void
    {
        $invokeMethod = $class->addMethod("__invoke");
        $invokeMethod
            ->addParameter($handlerClass->attributeToHandle()->name())
            ->setType($handlerClass->classToHandleNamespace()->namespace());
        
        $returnType = "void";
        if (!empty($handlerClass->responseClassNamespace()->namespace())) {
            $returnType = $handlerClass->responseClassNamespace()->namespace();
        }
        
        $invokeMethod->setBody("\$this->useCase->__invoke();");
        $invokeMethod->setReturnType($returnType);
    }
}
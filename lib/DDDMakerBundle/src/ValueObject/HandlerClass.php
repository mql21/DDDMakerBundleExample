<?php

namespace Mql21\DDDMakerBundle\ValueObject;

class HandlerClass extends DDDElement
{
    private ClassNamespace $classNamespace;
    private ClassName $className;
    private ClassNamespace $interfaceNamespace;
    private ClassNamespace $parentClassNamespace;
    private ClassNamespace $classToHandleNamespace;
    private AttributeName $attributeToHandle;
    private ClassNamespace $useCaseNamespace;
    private ClassName $useCaseName;
    private ClassName $classToHandleName;
    private ClassNamespace $responseClassNamespace;
    
    public function __construct(
        ClassNamespace $classNamespace,
        ClassName $className,
        ClassNamespace $interfaceNamespace,
        ClassNamespace $parentClassNamespace,
        ClassNamespace $classToHandleNamespace,
        ClassName $classToHandleName,
        AttributeName $attributeToHandle,
        ClassNamespace $useCaseNamespace,
        ClassName $useCaseName,
        ClassNamespace $classToReturnNamespace
    ) {
        $this->classNamespace = $classNamespace;
        $this->className = $className;
        $this->interfaceNamespace = $interfaceNamespace;
        $this->parentClassNamespace = $parentClassNamespace;
        $this->classToHandleNamespace = $classToHandleNamespace;
        $this->classToHandleName = $classToHandleName;
        $this->attributeToHandle = $attributeToHandle;
        $this->useCaseNamespace = $useCaseNamespace;
        $this->useCaseName = $useCaseName;
        $this->responseClassNamespace = $classToReturnNamespace;
    }
    
    public function classNamespace(): ClassNamespace
    {
        return $this->classNamespace;
    }
    
    public function className(): ClassName
    {
        return $this->className;
    }
    
    public function interfaceNamespace(): ClassNamespace
    {
        return $this->interfaceNamespace;
    }
    
    public function parentClassNamespace(): ClassNamespace
    {
        return $this->parentClassNamespace;
    }
    
    public function classToHandleNamespace(): ClassNamespace
    {
        return $this->classToHandleNamespace;
    }
    
    public function classToHandleName(): ClassName
    {
        return $this->classToHandleName;
    }
    
    public function attributeToHandle(): AttributeName
    {
        return $this->attributeToHandle;
    }
    
    public function useCaseNamespace(): ClassNamespace
    {
        return $this->useCaseNamespace;
    }
    
    public function useCaseName(): ClassName
    {
        return $this->useCaseName;
    }
    
    public function responseClassNamespace(): ClassNamespace
    {
        return $this->responseClassNamespace;
    }
}
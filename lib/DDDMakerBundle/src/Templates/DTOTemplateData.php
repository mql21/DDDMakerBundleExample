<?php

namespace Mql21\DDDMakerBundle\Templates;

class DTOTemplateData
{
    private string $baseClassNamespace;
    private string $className;
    private array $classAttributes;
    
    public function __construct(string $baseClassNamespace, string $className, array $classAttributes)
    {
        $this->baseClassNamespace = $baseClassNamespace;
        $this->className = $className;
        $this->classAttributes = $classAttributes;
    }
    
    public function baseClassNamespace()
    {
        return $this->baseClassNamespace;
    }
    
    public function getClassName()
    {
        return $this->className;
    }
    
    public function classAttributes()
    {
        return $this->classAttributes;
    }
}
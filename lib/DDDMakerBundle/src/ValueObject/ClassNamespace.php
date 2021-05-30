<?php

namespace Mql21\DDDMakerBundle\ValueObject;

class ClassNamespace
{
    private ?string $namespace;
    
    public function __construct(?string $namespace)
    {
        $this->namespace = $namespace;
    }
    
    public function namespace(): ?string
    {
        return $this->namespace;
    }
}
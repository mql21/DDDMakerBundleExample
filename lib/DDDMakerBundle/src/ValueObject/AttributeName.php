<?php

namespace Mql21\DDDMakerBundle\ValueObject;

class AttributeName
{
    private string $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function name(): string
    {
        return $this->name;
    }
}
<?php

namespace Mql21\DDDMakerBundle\ValueObject;

class ClassAttributes
{
    private array $attributes;
    
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
    
    public function attributes(): array
    {
        return $this->attributes;
    }
}
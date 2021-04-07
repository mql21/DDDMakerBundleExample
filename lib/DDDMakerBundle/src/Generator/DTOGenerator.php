<?php

namespace Mql21\DDDMakerBundle\Generator;

class DTOGenerator
{
    protected array $classAttributes;
    
    public function __construct(array $classAttributes)
    {
        $this->classAttributes = $classAttributes;
    }
}
<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Response\DTOAttributesResponse;

class DTOGenerator
{
    protected DTOAttributesResponse $classAttributes;
    
    public function __construct(DTOAttributesResponse $classAttributes)
    {
        $this->classAttributes = $classAttributes;
    }
}
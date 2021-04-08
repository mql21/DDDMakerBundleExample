<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Response\UseCaseResponse;

class HandlerGenerator
{
    protected UseCaseResponse $useCaseResponse;
    
    public function __construct(UseCaseResponse $useCaseResponse)
    {
        $this->useCaseResponse = $useCaseResponse;
    }
}
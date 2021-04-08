<?php

namespace Mql21\DDDMakerBundle\Response;

class UseCaseResponse
{
    private string $useCase;
    
    public function __construct(string $useCase)
    {
        $this->useCase = $useCase;
    }
    
    public function useCase(): string
    {
        return $this->useCase;
    }
}
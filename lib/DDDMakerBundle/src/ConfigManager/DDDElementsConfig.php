<?php

namespace Mql21\DDDMakerBundle\ConfigManager;

class DDDElementsConfig
{
    private array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public function config(): array
    {
        return $this->config;
    }
}
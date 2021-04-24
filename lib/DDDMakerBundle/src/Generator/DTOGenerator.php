<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Response\DTOAttributesResponse;

class DTOGenerator
{
    protected DTOAttributesResponse $classAttributes;
    protected ConfigManager $configManager;
    
    public function __construct(DTOAttributesResponse $classAttributes)
    {
        $this->classAttributes = $classAttributes;
        $this->configManager = new ConfigManager();
    }
}
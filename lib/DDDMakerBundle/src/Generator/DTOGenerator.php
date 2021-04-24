<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;
use Mql21\DDDMakerBundle\Response\DTOAttributesResponse;

class DTOGenerator
{
    protected DTOAttributesResponse $classAttributes;
    protected ConfigManager $configManager;
    protected PHPCodeRenderer $renderer;
    
    public function __construct(DTOAttributesResponse $classAttributes)
    {
        $this->classAttributes = $classAttributes;
        $this->configManager = new ConfigManager();
        $this->renderer = new PHPCodeRenderer();
    }
}
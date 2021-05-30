<?php

namespace Mql21\DDDMakerBundle\Generator\Handler;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Renderer\HandlerRenderer;
use Mql21\DDDMakerBundle\Response\UseCaseResponse;

class HandlerGenerator
{
    protected UseCaseResponse $useCaseResponse;
    protected ConfigManager $configManager;
    protected HandlerRenderer $renderer;
    
    public function __construct(UseCaseResponse $useCaseResponse)
    {
        $this->useCaseResponse = $useCaseResponse;
        $this->configManager = new ConfigManager();
        $this->renderer = new HandlerRenderer();
    }
}
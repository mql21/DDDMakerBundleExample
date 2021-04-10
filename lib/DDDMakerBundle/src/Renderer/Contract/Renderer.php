<?php

declare(strict_types=1);

namespace Mql21\DDDMakerBundle\Renderer\Contract;

interface Renderer
{
    public function render(string $templatePath, array $templateVars): string;
}
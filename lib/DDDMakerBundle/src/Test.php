<?php

namespace Mql21\DDDMakerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Test extends Bundle
{
    public function __invoke()
    {
        return 1+1;
    }
}
<?php

namespace OCSymfony\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OCSymfonyUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

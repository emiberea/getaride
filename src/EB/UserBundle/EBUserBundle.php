<?php

namespace EB\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EBUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

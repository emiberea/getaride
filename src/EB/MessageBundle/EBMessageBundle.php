<?php

namespace EB\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EBMessageBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}

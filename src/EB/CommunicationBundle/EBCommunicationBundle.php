<?php

namespace EB\CommunicationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EBCommunicationBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}

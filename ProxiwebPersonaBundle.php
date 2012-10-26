<?php

namespace Proxiweb\Bundle\PersonaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Proxiweb\Bundle\PersonaBundle\Security\Factory\PersonaFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProxiwebPersonaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new PersonaFactory());
    }
}

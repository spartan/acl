<?php

namespace Spartan\Acl;

use Psr\Container\ContainerInterface;
use Spartan\Acl\Adapter\Php;
use Spartan\Acl\Definition\AclInterface;
use Spartan\Service\Container;
use Spartan\Service\Definition\ProviderInterface;
use Spartan\Service\Pipeline;

/**
 * ServiceProvider Logger
 *
 * @package Spartan\Logger
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ServiceProvider implements ProviderInterface
{
    /**
     * @return mixed[]
     */
    public function singletons(): array
    {
        return [
            'acl'               => AclInterface::class,
            AclInterface::class => function ($c) {
                return new Php(require_once './config/acl.php');
            },
        ];
    }

    /**
     * @param ContainerInterface $container
     * @param Pipeline           $handler
     *
     * @return ContainerInterface
     */
    public function process(ContainerInterface $container, Pipeline $handler): ContainerInterface
    {
        /** @var Container $container */
        return $handler->handle(
            $container->withBindings($this->singletons())
        );
    }
}

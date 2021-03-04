<?php

namespace Spartan\Acl;

use Psr\Container\ContainerInterface;
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
    /** @var mixed[] */
    protected array $config = [];

    /**
     * ServiceProvider constructor
     */
    public function __construct()
    {
        $this->config = require_once './config/cache.php';
    }

    /**
     * @return mixed[]
     */
    public function singletons(): array
    {
        return [
            'acl'               => AclInterface::class,
            AclInterface::class => function ($c) {
                $adapterName    = $this->config['adapter'];
                $adapterClass   = 'Spartan\\Acl\\Adapter\\' . ucfirst($adapterName);
                $adapterOptions = $this->config[$adapterName];

                return new $adapterClass($adapterOptions);
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

<?php

use Spartan\Acl\Adapter\Php;
use Spartan\Acl\Definition\AclInterface;

if (!function_exists('acl')) {
    /**
     * @return AclInterface|Php
     * @throws ReflectionException
     * @throws \Spartan\Service\Exception\ContainerException
     * @throws \Spartan\Service\Exception\NotFoundException
     */
    function acl(): AclInterface
    {
        return container()->get(AclInterface::class);
    }
}

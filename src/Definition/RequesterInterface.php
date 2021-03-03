<?php

namespace Spartan\Acl\Definition;

interface RequesterInterface
{
    /**
     * @return mixed
     */
    public function id();

    /**
     * @return mixed[]
     */
    public function data(): array;

    /**
     * @return string[]
     */
    public function roles(): array;
}

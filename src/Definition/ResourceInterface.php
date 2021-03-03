<?php

namespace Spartan\Acl\Definition;

interface ResourceInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return mixed
     */
    public function id();

    /**
     * @return mixed
     */
    public function ownerId();

    /**
     * @return mixed[]
     */
    public function data(): array;
}

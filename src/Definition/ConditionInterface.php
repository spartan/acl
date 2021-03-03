<?php

namespace Spartan\Acl\Definition;

interface ConditionInterface
{
    /**
     * @param RequesterInterface $requester
     * @param string             $operation
     * @param ResourceInterface  $resource
     * @param mixed              $payload
     *
     * @return bool
     */
    public function __invoke(
        RequesterInterface $requester,
        string $operation,
        ResourceInterface $resource,
        $payload = null
    ): bool;
}

<?php

namespace Spartan\Acl\Test\Condition;

use Spartan\Acl\Definition\ConditionInterface;
use Spartan\Acl\Definition\RequesterInterface;
use Spartan\Acl\Definition\ResourceInterface;

class Ownership implements ConditionInterface
{
    public function __invoke(RequesterInterface $requester, string $operation, ResourceInterface $resource, $payload = null): bool
    {
        return $resource->ownerId() == $requester->id();
    }
}

<?php

namespace Spartan\Acl\Definition;

interface AclInterface
{
    const ANY     = '*';
    const READ    = 'read';
    const CREATE  = 'create';
    const UPDATE  = 'update';
    const DELETE  = 'delete';
    const MUTATE  = [self::CREATE, self::UPDATE, self::DELETE];
    const SEARCH  = 'search';
    const EXECUTE = 'execute';

    /**
     * @param mixed $requester
     * @param mixed $operation
     * @param mixed $resource
     * @param mixed $payload
     *
     * @return bool
     */
    public function isAllowed($requester, $operation, $resource, $payload = null): bool;
}

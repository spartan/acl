<?php

namespace Spartan\Acl\Adapter;

use Spartan\Acl\Definition\AclInterface;
use Spartan\Acl\Definition\RequesterInterface;
use Spartan\Acl\Definition\ResourceInterface;
use Spartan\Acl\Requester;
use Spartan\Acl\Resource;

class Php implements AclInterface
{
    /**
     * @var array[]
     */
    protected array $config = [
        'roles' => [],
        'perms' => [],
    ];

    /**
     * Php constructor.
     *
     * @param array[] $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['file'])) {
            $this->withConfig(require_once $options['file']);
        } elseif (isset($options['config'])) {
            $this->withConfig($options['config']);
        }
    }

    /**
     * @return array[]
     */
    public function config(): array
    {
        return $this->config;
    }

    /**
     * @param array[] $config
     *
     * @return $this
     */
    public function withConfig(array $config): self
    {
        $this->config = $config + $this->config;

        return $this;
    }

    /**
     * @param mixed    $id
     * @param string[] $inherit
     *
     * @return $this
     */
    public function withRole($id, array $inherit = []): self
    {
        $this->config['roles'][$id] = $inherit;

        return $this;
    }

    /**
     * @param mixed   $requester
     * @param mixed   $operations
     * @param mixed   $resource
     * @param mixed[] $conditions
     *
     * @return $this
     */
    public function grant($requester, $operations, $resource, array $conditions = []): self
    {
        if (!($requester instanceof RequesterInterface)) {
            $requester = new Requester($requester);
        }

        if (!($resource instanceof ResourceInterface)) {
            $resource = new Resource($resource);
        }

        if (!$operations) {
            $operations = self::ANY;
        }

        foreach ((array)$operations as $operation) {
            $this->config['perms'][$requester->id()][$resource->name()][$resource->id()][$operation] = $conditions ?: true;
        }

        return $this;
    }

    /**
     * @param mixed $requester
     * @param mixed $operations
     * @param mixed $resource
     *
     * @return $this
     */
    public function deny($requester, $operations, $resource): self
    {
        if (!($requester instanceof RequesterInterface)) {
            $requester = new Requester($requester);
        }

        if (!($resource instanceof ResourceInterface)) {
            $resource = new Resource($resource);
        }

        if (!$operations) {
            $operations = self::ANY;
        }

        foreach ((array)$operations as $operation) {
            if (!$operation) {
                $operation = self::ANY;
            }
            $this->config['perms'][$requester->id()][$resource->name()][$resource->id()][$operation] = false;
        }

        return $this;
    }

    /**
     * @param mixed $requester
     * @param mixed $operation
     * @param mixed $resource
     * @param mixed $payload
     *
     * @return bool
     */
    public function isAllowed($requester, $operation, $resource, $payload = null): bool
    {
        if (!($requester instanceof RequesterInterface)) {
            $requester = new Requester($requester);
        }

        if (!($resource instanceof ResourceInterface)) {
            $resource = new Resource($resource);
        }

        if (!$operation) {
            $operation = self::ANY;
        }

        $roles = [$requester->id(), ...$requester->roles(), ...$this->config['roles'][$requester->id()] ?? []];
        foreach ($requester->roles() as $requesterRole) {
            $roles = [...$roles, ...$this->inheritedRoles($requesterRole)];
        }

        foreach ($roles as $role) {
            $conditions = $this->config['perms'][$role][$resource->name()][$resource->id()][$operation]
                ?? ($this->config['perms'][$role][$resource->name()][$resource->id()][self::ANY]
                    ?? ($this->config['perms'][$role][$resource->name()][self::ANY][self::ANY]
                        ?? []));

            if ($conditions == []) {
                continue;
            } elseif (is_array($conditions)) {
                foreach ($conditions as $condition) {
                    if (is_string($condition)) {
                        $condition = new $condition;
                    }

                    if ($condition($requester, $operation, $resource, $payload) === false) {
                        return false;
                    }
                }

                return true;
            } else {
                return $conditions;
            }
        }

        return false;
    }

    /**
     * @param int|string $roleId
     *
     * @return string[]
     */
    public function inheritedRoles($roleId)
    {
        $roles = (array)($this->config['roles'][$roleId] ?? []);

        foreach ($roles as $parentRoleId) {
            $roles = [...$roles, ...$this->inheritedRoles($parentRoleId)];
        }

        return $roles;
    }
}

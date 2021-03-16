<?php

namespace Spartan\Acl;

use Spartan\Acl\Adapter\Php;
use Spartan\Acl\Definition\ResourceInterface;

class Resource implements ResourceInterface
{
    protected string $name;

    /**
     * @var mixed[]
     */
    protected array $data = [];

    /**
     * Resource constructor.
     *
     * @param string  $name
     * @param mixed[] $data
     */
    public function __construct(string $name, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return mixed|string
     */
    public function id()
    {
        return $this->data['id'] ?? '';
    }

    /**
     * @return mixed|null
     */
    public function ownerId()
    {
        return $this->data['created_by'] ?? null;
    }

    /**
     * @return mixed[]
     */
    public function data(): array
    {
        return $this->data;
    }
}

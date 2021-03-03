<?php

namespace Spartan\Acl;

use Spartan\Acl\Definition\RequesterInterface;

class Requester implements RequesterInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed[]
     */
    protected array $data;

    /**
     * @var string[]
     */
    protected array $roles;

    /**
     * Requester constructor.
     *
     * @param mixed    $id
     * @param string[] $roles
     * @param mixed[]  $data
     */
    public function __construct($id, array $roles = [], array $data = [])
    {
        $this->id    = $id;
        $this->roles = $roles;
        $this->data  = $data;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return array|mixed[]
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @return array|string[]
     */
    public function roles(): array
    {
        return $this->roles;
    }
}

<?php

namespace Spartan\Acl\Test;

use PHPUnit\Framework\TestCase;
use Spartan\Acl\Adapter\Php;
use Spartan\Acl\Requester;
use Spartan\Acl\Resource;
use Spartan\Acl\Test\Condition\Ownership;
use Spartan\Acl\Test\Resource\Post;

class PhpAclTest extends TestCase
{
    public function testAllowToParentRole()
    {
        $acl = (new Php())
            ->withRole('manager', ['moderator'])
            ->grant('moderator', Php::UPDATE, Post::class);

        $this->assertTrue($acl->isAllowed('manager', Php::UPDATE, Post::class));
    }

    public function testAllowToAny()
    {
        $acl = (new Php())
            ->withRole('moderator', ['manager'])
            ->grant('manager', null, Post::class);

        $this->assertTrue($acl->isAllowed('moderator', Php::UPDATE, Post::class));
    }

    public function testWithCondition()
    {
        $acl = (new Php())
            ->withRole('moderator', ['manager'])
            ->grant('manager', null, Post::class, [Ownership::class]);

        $this->assertTrue(
            $acl->isAllowed(new Requester(1, ['moderator']), Php::UPDATE, new Resource(Post::class, ['id' => 1, 'created_by' => 1]))
        );
    }

    public function testDeniedByInheritedRole()
    {
        $acl = (new Php())
            ->withRole('manager', ['user'])
            ->deny('user', null, Post::class);

        $this->assertFalse($acl->isAllowed('manager', Php::UPDATE, Post::class));
    }

    public function testNonExistingRole()
    {
        $acl = (new Php());

        $this->assertFalse($acl->isAllowed('manager', Php::UPDATE, Post::class));
    }
}

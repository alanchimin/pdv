<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\User;

class UserTest extends TestCase
{
    public function testFindByNameReturnsUser()
    {
        $model = new User();
        $user = $model->findByName('admin');

        $this->assertIsArray($user);
        $this->assertEquals('admin', $user['name']);
    }
}

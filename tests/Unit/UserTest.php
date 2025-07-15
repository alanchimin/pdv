<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\User;
use Tests\Traits\GlobalResetTrait;

class UserTest extends TestCase
{
    use GlobalResetTrait;

    /**
     * Testa se o método findByName retorna um array contendo os dados do usuário 'admin'.
     */
    public function testFindByNameReturnsUser()
    {
        $model = new User();
        $user = $model->findByName('admin');

        $this->assertIsArray($user);
        $this->assertEquals('admin', $user['name']);
    }
}

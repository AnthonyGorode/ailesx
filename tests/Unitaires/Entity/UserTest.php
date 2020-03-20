<?php

namespace App\tests\Unitaires\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase{

    private $user;

    public function setUp(){
        $this->user = new User();
    }

    /**
     * @test
     */
    function testSlugify(){

        $this->user->setFirstName("Jean")
                   ->setLastName("Dupont");

        $this->user->initializeSlug();

        $this->assertSame("jeandupont",$this->user->getSlug());
    }

    /**
     * @test
     */
    function testgetRoles(){

        $this->assertContains("ROLE_USER",$this->user->getRoles());

    }
}
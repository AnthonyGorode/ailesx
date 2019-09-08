<?php

Namespace App\Tests\Service;

use App\Entity\User;
use Twig\Environment;
use App\Service\PaginatorDql;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorDqlTest extends TestCase
{
    public function testPaginatorDql(){
        $paginatorDql = new PaginatorDql(ObjectManager::class, Environment::class, RequestStack::class);
        $result = $paginatorDql->setEntityClass(User::class)
                               ->setColumn('u.id')
                               ->setOrder('ASC')
                               ->setConcat(0)
                               ->setPage(1)
                               ->getPages();                              

        $this->assertEquals(6,$result);
    }
}

?>
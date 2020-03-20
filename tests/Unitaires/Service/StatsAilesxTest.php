<?php

namespace App\tests\Unitaires\Service;

use App\Service\StatsAilesx;
use PHPUnit\Framework\TestCase;

class StatsAilesxTest extends TestCase{

    /**
     * @var ObjectManager
     */
    private $manager;
     
    /**
     * @var NewsStatsAilesx
     */
    private $statsAilesxTest; 
     
    public function setUp()
    {
        $this->manager  = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
                               ->disableOriginalConstructor()
                               ->getMock();
 
        $this->statsAilesxTest = new StatsAilesx($this->manager);  
    }

    public function testDestinationStats(){
                        
        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
                      ->disableOriginalConstructor()
                      ->getMock();

        $this->manager->expects($this->once())
                      ->method('createQuery')
                      ->will($this->returnValue($query));
                        
        // $hydrator = $this->getMockBuilder('Doctrine\ORM\Internal\Hydration\AbstractHydrator')
        //                 ->disableOriginalConstructor()
        //                 ->getMockForAbstractClass();

        // $query->expects($this->once())
        //         ->method('newHydrator')
        //         ->will($this->returnValue($hydrator));
        // $manager->method('createQuery')->willReturn($query);


                         
        // $query->method('newHydrator')->WillReturn($hydrator);

        // $entityInterface = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
        //                         ->setMethods(['newHydrator'])
        //                         ->getMockForAbstractClass();
        
        $result = $this->statsAilesxTest->getStats(); 

        $this->assertContains(11,$result);
   }

}
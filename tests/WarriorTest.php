<?php
/**
 * Created by PhpStorm.
 * User: alexandragalbeaza
 * Date: 10/31/18
 * Time: 11:28 AM
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use RolePlay\Warrior;
use RolePlay\Skills;
use Mockery as m;

class WarriorTest extends TestCase
{
    /**
     * @var $warrior Warrior
     */
    private $warrior;
    private $health;
    private $strength;
    private $defence;
    private $speed;
    private $luck;

    public function setUp()
    {
        $warriorStats = [
            'health' => [
                'from' => 70,
                'to' => 100
            ],
            'strength' => [
                'from' => 70,
                'to' => 80
            ],
            'defence' => [
                'from' => 45,
                'to' => 55
            ],
            'speed' => [
                'from' => 40,
                'to' => 50
            ],
            'luck' => [
                'from' => 10,
                'to' => 30
            ]
        ];

        mt_srand(0);
        $this->warrior = new Warrior('Stanhope', $warriorStats);

        mt_srand(0);
        $this->health = mt_rand(70, 100);
        $this->strength = mt_rand(70, 80);
        $this->defence = mt_rand(45, 55);
        $this->speed = mt_rand(40, 50);
        $this->luck = mt_rand(10, 30);
    }
    public function testGetName()
    {
        $this->assertEquals('Stanhope', $this->warrior->getName());
    }
    public function testGetStats()
    {
        $this->assertEquals($this->health, $this->warrior->getHealth());
        $this->assertEquals($this->strength, $this->warrior->getStrength());
        $this->assertEquals($this->defence, $this->warrior->getDefence());
        $this->assertEquals($this->speed, $this->warrior->getSpeed());
        $this->assertEquals($this->luck, $this->warrior->getLuck());
    }

    public function testSetIsLucky()
    {
        mt_srand(0);
        $this->warrior->setIsLucky();

        mt_srand(0);
        $luckyValue = mt_rand(0, 99);

        $this->assertEquals($this->warrior->checkIsLucky(), $luckyValue < $this->luck);
    }

    public function testWarriorInDefenceWithSkill()
    {
        $skill =  $this->getMockBuilder('RolePlay\Skills')
            ->setConstructorArgs(array('SkillName', true, 2, 20))
            ->getMock();

        $skill->expects($this->any())
            ->method("inUse")
            ->will($this->returnValue(true));
        $skill->expects($this->any())
            ->method("getValue")
            ->will($this->returnValue(true));


        $this->warrior->addSkill($skill);
        $this->warrior->inDefence();

        $this->assertNotNull($this->warrior->getUsedSkill());
    }

    public function testWarriorInDefenceNoDefenceSkillGotLucky()
    {
        mt_srand(0);
        $this->warrior->inDefence();

        mt_srand(0);
        $luckyValue = mt_rand(0, 99);

        $this->assertEquals($this->warrior->checkIsLucky(), $luckyValue < $this->luck);
    }

    public function testWarriorInAttackWithSkill()
    {
        $skill =  $this->getMockBuilder('RolePlay\Skills')
            ->setConstructorArgs(array('SkillName', false, 2, 20))
            ->getMock();

        $skill->expects($this->any())
            ->method("inUse")
            ->will($this->returnValue(true));
        $skill->expects($this->any())
            ->method("getValue")
            ->will($this->returnValue(true));

        $this->warrior->addSkill($skill);
        $this->warrior->inAttach();

        $this->assertNotNull($this->warrior->getUsedSkill());

    }
    public function testDamageGotLucky()
    {
        $warrior = m::mock('RolePlay\Warrior')->makePartial();
        $warrior->shouldReceive('checkIsLucky')
            ->andReturn(true);

        $damage = 40;
        $warrior->updateDamage($damage);

        $this->assertEquals($warrior->getDamage(), 0);
    }

    public function testDamageGotDefenceSkill()
    {
        $skill = $this->getMockBuilder('RolePlay\Skills')
            ->setConstructorArgs(array('SkillName', true, 0.5, 20))
            ->getMock();

        $skill->expects($this->any())
            ->method("inUse")
            ->will($this->returnValue(true));
        $skill->expects($this->any())
            ->method("inDefence")
            ->will($this->returnValue(true));
        $skill->expects($this->any())
            ->method("getValue")
            ->will($this->returnValue(0.5));

        $this->warrior->addSkill($skill);
        $this->warrior->inDefence();

        $damage = 40;
        $this->warrior->updateDamage($damage);

        $this->assertEquals( 20, $this->warrior->getDamage());
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: alexandragalbeaza
 * Date: 10/30/18
 * Time: 3:46 PM
 */

namespace RolePlay;

class Warrior
{
    private $name;
    private $health = 40;
    private $strength = 40;
    private $defence = 40;
    private $speed = 40;
    private $luck = 10;

    private $skills = [];

    private $damage;
    private $isLucky;
    private $inDefence;

    public function __construct(string $name, array $stats)
    {
        $this->name = $name;
        $this->damage = 0;
        $this->isLucky = false;
        $this->inDefence = false;
        $this->setStats($stats);
    }

    private function setStats($stats)
    {
        foreach ($stats as $key => $stat) {
            switch ($key) {
                case 'health':
                    $this->setHealth($stat['from'], $stat['to']);

                    break;
                case 'strength':
                    $this->setStrength($stat['from'], $stat['to']);

                    break;
                case 'defence':
                    $this->setDefence($stat['from'], $stat['to']);

                    break;
                case 'speed':
                    $this->setSpeed($stat['from'], $stat['to']);

                    break;
                case 'luck':
                    $this->setLuck($stat['from'], $stat['to']);
                    break;
            }
        }
    }

    public function getRandomNumber($min, $max): int
    {
        return mt_rand($min, $max);
    }

    public function addSkill(Skills $skill)
    {
        $this->skills[] = $skill;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setHealth($from, $to)
    {
        $this->health = $this->getRandomNumber($from, $to);
    }
    public function getHealth()
    {
        return $this->health;
    }
    public function setStrength($from, $to)
    {
        $this->strength = $this->getRandomNumber($from, $to);
    }
    public function getStrength(): int
    {
        return $this->strength;
    }
    public function setDefence($from, $to)
    {
        $this->defence = $this->getRandomNumber($from, $to);
    }
    public function getDefence(): int
    {
        return $this->defence;
    }
    public function setSpeed($from, $to)
    {
        $this->speed = $this->getRandomNumber($from, $to);
    }
    public function getSpeed(): int
    {
        return $this->speed;
    }
    public function setLuck($from, $to)
    {
        $this->luck = $this->getRandomNumber($from, $to);
    }
    public function getLuck(): int
    {
        return $this->luck;
    }
    public function setInDefence(bool $inDefence)
    {
        $this->inDefence = $inDefence;
    }

    public function setIsLucky()
    {
        $rand = $this->getRandomNumber(0, 99);
        $this->isLucky = ($rand < $this->luck);
    }

    public function checkIsLucky(): bool
    {
        return $this->isLucky;
    }

    public function getUsedSkill(): ?Skills
    {
        foreach ($this->skills as $skill) {
            if ($skill->inUse()) {
                return $skill;
            }
        }

        return null;
    }

    public function inAttach()
    {
        $this->inDefence = false;
        $this->damage = 0;

        foreach ($this->skills as $skill) {
            if ($skill->inUse() && $skill->inDefence()) {
                $skill->setInUse(true);
            }

            if (!$skill->inDefence()) {
                $skill->setInUse();
            }
            // warrior can une only one skill on attach
            if ($skill->inUse()) {
                break;
            }
        }
    }

    public function inDefence()
    {
        $this->inDefence = true;
        $this->damage = 0;

        $this->setIsLucky();

        foreach ($this->skills as $skill) {
            if ($skill->inUse() && !$skill->inDefence()) {
                $skill->setInUse(true);
            }

            if ($skill->inDefence() && !$this->checkIsLucky()) {
                $skill->setInUse();
            }

            // warrior can une only one skill on defence
            if ($skill->inUse()) {
                break;
            }
        }
    }
    public function updateDamage($damage)
    {
        if ($this->checkIsLucky()) {
            $this->damage = 0;

            return;
        }

        foreach ($this->skills as $skill) {
            if ($skill->inUse() && $skill->inDefence()) {
                $damage = $damage * $skill->getValue();
            }
        }

        $this->damage = $damage;
        $this->health = $this->health - $damage;

        if ($this->health < 0) {
            $this->health = 0;
        }
    }
    public function getDamage()
    {
        return $this->damage;
    }

}
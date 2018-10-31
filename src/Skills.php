<?php
/**
 * Created by PhpStorm.
 * User: alexandragalbeaza
 * Date: 10/30/18
 * Time: 6:03 PM
 */
namespace RolePlay;

class Skills
{
    private $name;
    private $inDefence;
    private $inUse;
    private $value;
    private $chance;

    public function __construct(string $name, bool $inDefence, float $value, int $chance)
    {
        $this->name = $name;
        $this->inDefence = $inDefence;
        $this->value = $value;
        $this->inUse = false;
        $this->chance = $chance;
    }

    public function setInUse(bool $forceFalse = false)
    {
        if ($forceFalse) {
            $this->inUse = false;
        } else {
            $this->inUse = $this->canBeUsed();
        }
    }

    public function inUse() : bool
    {
        return $this->inUse;
    }

    public function inDefence(): bool
    {
        return $this->inDefence;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function canBeUsed(): bool
    {
        $rand = mt_rand(0, 99);

        return $this->chance > $rand;
    }
}
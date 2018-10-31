<?php
/**
 * Created by PhpStorm.
 * User: alexandragalbeaza
 * Date: 10/30/18
 * Time: 6:02 PM
 */

namespace RolePlay;

class Fight
{
    private $goodBoy;
    private $badBoy;
    private $turn = 0;
    private $totalTurns = 0;
    private $results = [];
    private $winner;

    public function __construct($turns)
    {
        $this->totalTurns = $turns;
    }

    public function setTurns($turns)
    {
        $this->totalTurns = $turns;
    }
    public function setOpponent(Warrior $warrior, bool $goodBoy = true)
    {
        if ($goodBoy) {
            $this->goodBoy = $warrior;
        } else {
            $this->badBoy = $warrior;
        }
    }

    public function startFight()
    {
        if ($this->totalTurns == 0) {
            throw new \Exception('Set the number of rounds');
        }
        $this->turn = 1;

        if ($this->goodBoy == null || $this->badBoy == null) {
            throw new \Exception('Setup the warriors');
        }

        if ($this->goodBoy->getSpeed() > $this->badBoy->getSpeed()) {
            $this->fight($this->goodBoy, $this->badBoy);

            return;
        }

        if (
            ($this->goodBoy->getSpeed() == $this->badBoy->getSpeed()) &&
            ($this->goodBoy->getLuck() > $this->badBoy->getLuck())
        ) {

            $this->fight($this->goodBoy, $this->badBoy);

            return;
        }

        $this->fight($this->badBoy, $this->goodBoy);
    }
    public function showResults()
    {
        foreach ($this->results as $index => $result) {

            echo '__________ Turn ' . $index . "________\n";
            echo $result['attacker'] . ' is attacking ' . $result['defender'] . "\n";

            if ($result['lucky']) {
                echo $result['defender'] . ' got lucky and ' . $result['attacker'] . ' missed' . "\n";
            }

            if ($result['defence_skill'] != '') {
                echo $result['defender'] . ' used ' . $result['defence_skill'] . ' skill' . "\n";
            }

            if ($result['attack_skill'] != '') {
                echo $result['attacker'] . ' used ' . $result['attack_skill'] . ' skill' . "\n";
            }

            echo "Damage: " . $result['damage'] . "\n";
            echo "Health: " . $result['health'] . "\n";
        }
    }
    public function showTheWinner()
    {
        if ($this->turn == 0) {
            echo 'no fight started';
        }

        echo "_______ The Winner is " . $this->winner->getName() . ' ____________' . "\n";
    }

    private function fight(Warrior $attacker, Warrior $defender)
    {
        $attacker->inAttach();
        $defender->inDefence();

        $attackerSkillUsed = $attacker->getUsedSkill();

        $damage = $attacker->getStrength() - $defender->getDefence();

        if ($damage < 0) {
            $damage = 0;
        }

        // double strikes means double damage :D
        if (!is_null($attackerSkillUsed)) {
            $damage = $damage * $attackerSkillUsed->getValue();
        }

        $defender->updateDamage($damage);

        // check if defender is dead or the number of rounds
        if ($defender->getHealth() > 0 && $this->turn <= $this->totalTurns) {
            $this->saveResults($attacker, $defender);

            $this->turn++;
            $this->fight($defender, $attacker);

        } else {

            $this->saveResults($attacker, $defender);
            $this->setTheWinner($attacker, $defender);
        }
    }
    private function saveResults(Warrior $attacker, Warrior $defender)
    {
        $defenderSkillUsed = $defender->getUsedSkill();
        $attackerSkillUsed = $attacker->getUsedSkill();

        $this->results[$this->turn] = [
            'attacker' => $attacker->getName(),
            'defender' => $defender->getName(),
            'lucky' => $defender->checkIsLucky(),
            'defence_skill' => '',
            'attack_skill' => '',
            'damage' =>  $defender->getDamage(),
            'health' => $defender->getHealth()
        ];

        if ($defenderSkillUsed != null) {
            $this->results[$this->turn]['defence_skill'] = $defenderSkillUsed->getName();
        }

        if ($attackerSkillUsed != null) {
            $this->results[$this->turn]['attack_skill'] = $attackerSkillUsed->getName();
        }
    }
    private function setTheWinner(Warrior $attacker, Warrior $defender)
    {
        if ($attacker->getHealth() == 0) {
            $this->winner = $defender;

            return;
        }

        if ($defender->getHealth() == 0) {
            $this->winner = $attacker;

            return;
        }

        if ($attacker->getHealth() > $defender->getHealth()) {
            $this->winner = $attacker;

            return;
        }

        $this->winner = $defender;
    }
}
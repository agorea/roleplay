<?php

require_once "vendor/autoload.php"; // composer's autoloader

use RolePlay\Warrior;
use RolePlay\Fight;
use RolePlay\Skills;

$warrior1Stats = [
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

$warrior2Stats = [
    'health' => [
        'from' => 60,
        'to' => 90
    ],
    'strength' => [
        'from' => 60,
        'to' => 90
    ],
    'defence' => [
        'from' => 40,
        'to' => 60
    ],
    'speed' => [
        'from' => 40,
        'to' => 60
    ],
    'luck' => [
        'from' => 25,
        'to' => 40
    ]
];

$warrior1 = new Warrior('Stanhope', $warrior1Stats);

$warrior1->addSkill(new Skills('Magic shield', true,0.5, 20));
$warrior1->addSkill(new Skills('Rapid strike', false,2, 10));

$warrior2 = new Warrior('wild beast', $warrior2Stats);

echo '--------- warrior 1: ' . $warrior1->getName() . '-------------' . "\n";
echo 'Health: ' . $warrior1->getHealth() . "\n";
echo 'Strength: ' . $warrior1->getStrength() . "\n";
echo 'Defence: ' . $warrior1->getDefence() . "\n";
echo 'Speed: ' . $warrior1->getSpeed() . "\n";
echo 'Luck: ' . $warrior1->getLuck() . "\n";

echo '--------- warrior 2: ' . $warrior2->getName() . '-------------' . "\n";
echo 'Health: ' . $warrior2->getHealth() . "\n";
echo 'Strength: ' . $warrior2->getStrength() . "\n";
echo 'Defence: ' . $warrior2->getDefence() . "\n";
echo 'Speed: ' . $warrior2->getSpeed() . "\n";
echo 'Luck: ' . $warrior2->getLuck() . "\n";

$aFight = new Fight(20);
$aFight->setOpponent($warrior1);
$aFight->setOpponent($warrior2, false);

echo "______________________________________________\n";
echo "Start fight: \n";

try {

    $aFight->startFight();

} catch (\Exception $ex) {
    echo $ex->getMessage() . "\n";
    return;
}

$aFight->showResults();
$aFight->showTheWinner();
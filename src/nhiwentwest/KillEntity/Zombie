<?php

namespace nhiwentwest\KillEntity;

use pocketmine\entity\Entity;
use pocketmine\scheduler\Task;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;

class Zombie extends Task{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->plugin->getScheduler()->scheduleRepeatingTask($this, 20 * 5); // Spawn Zombie every 5 seconds
    }

    public function onRun(int $currentTick){
        $config = $this->plugin->getConfig();
        $x = $config->get("x");
        $y = $config->get("y");

        /** @var Level $level */
        $level = $this->plugin->getServer()->getDefaultLevel();
        $entities = $level->getEntities();

        // Count the number of Zombies in the specified area
        $zombieCount = 0;
        foreach ($entities as $entity) {
            if ($entity instanceof \pocketmine\entity\Zombie && $entity->getX() >= $x && $entity->getX() <= $x + 16 && $entity->getY() >= $y && $entity->getY() <= $y + 16) {
                $zombieCount++;
            }
        }

        // Check if the number of Zombies is less than 5
        if ($zombieCount < 5) {
            // Spawn Zombie every 5 seconds
            $this->spawnZombie($x, $y);
        }
    }

    public function spawnZombie(float $x, float $y){
        $level = $this->plugin->getServer()->getDefaultLevel();
        $spawnX = $x + mt_rand(0, 16);
        $spawnY = $y + mt_rand(0, 16);
        $spawnZ = $level->getHighestBlockAt($spawnX, $spawnY);
        $zombie = Entity::createEntity("Zombie", $level->getChunk($spawnX >> 4, $spawnZ >> 4), Entity::createBaseNBT(new Vector3($spawnX, $spawnY, $spawnZ)));
        
        if ($zombie !== null) {
            foreach ($level->getPlayers() as $player) {
                $zombie->spawnTo($player);
            }
        }
    }
}

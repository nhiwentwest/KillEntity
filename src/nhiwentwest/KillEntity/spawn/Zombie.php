<?php

namespace nhiwentwest\KillEntity\spawn;

use pocketmine\entity\Entity;
use pocketmine\scheduler\Task;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use nhiwentwest\KillEntity\Main;

class Zombie extends Task{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->plugin->getScheduler()->scheduleRepeatingTask($this, 20 * 5); // Spawn Zombie every 5 seconds
    }

    public function onRun(int $currentTick = -1) : void {
        $config = $this->plugin->getConfig();
        $x = $config->get("x");
        $y = $config->get("y");

        /** @var World $world */
        $world = $this->plugin->getServer()->getDefaultLevel();

        $entities = $world->getEntities();

        // Count the number of Zombies in the specified area
        $zombieCount = 0;
     foreach ($entities as $entity) {
    if ($entity instanceof \pocketmine\entity\Zombie) {
        $entityLocation = $entity->getLocation();
        $entityX = $entityLocation->x;
        $entityY = $entityLocation->y;
        if ($entityX >= $x && $entityX <= $x + 16 && $entityY >= $y && $entityY <= $y + 16) {
            $zombieCount++;
        }
    }
}

        

        // Check if the number of Zombies is less than 5
        if ($zombieCount < 5) {
            // Spawn Zombie every 5 seconds
            $this->spawnZombie($x, $y);
        }
    }

    public function spawnZombie(float $x, float $y){
        $world = $this->plugin->getServer()->getDefaultLevel();
        $spawnX = $x + mt_rand(0, 16);
        $spawnY = $y + mt_rand(0, 16);
        $spawnZ = $world->getHighestBlockAt($spawnX, $spawnY);
        $zombie = Entity::createEntity("Zombie", $world->getChunk($spawnX >> 4, $spawnZ >> 4), Entity::createBaseNBT(new Vector3($spawnX, $spawnY, $spawnZ)));
        
        if ($zombie !== null) {
            foreach ($world->getPlayers() as $player) {
                $zombie->spawnTo($player);
            }
        }
    }
}

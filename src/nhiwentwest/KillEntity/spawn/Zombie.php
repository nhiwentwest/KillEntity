<?php

namespace nhiwentwest\KillEntity\spawn;

use pocketmine\entity\Entity;
use pocketmine\scheduler\Task;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use pocketmine\entity\Location;
use nhiwentwest\KillEntity\Main;

class Zombie extends Task{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
        $this->plugin->getScheduler()->scheduleRepeatingTask($this, 20 * 5); // Spawn Zombie every 5 seconds
     
    }

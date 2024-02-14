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

    public function spawnEntityInSquareFromConfig(string $mobName, World $world, string $configFilePath) {
        // Lấy dữ liệu từ tệp cấu hình
        $configData = yaml_parse_file($configFilePath);
        if ($configData === false || !isset($configData['x1']) || !isset($configData['y1']) || !isset($configData['x2']) || !isset($configData['y2'])) {
            return Main::$instance->getServer()->getLogger()->info("§cError§f loading or invalid config file §d$configFilePath §r");
        }

        // Lấy toạ độ từ config
        $x1 = (int) $configData['x1'];
        $y1 = (int) $configData['y1'];
        $x2 = (int) $configData['x2'];
        $y2 = (int) $configData['y2'];

        // Xác định các giới hạn của hình vuông
        $minX = min($x1, $x2);
        $maxX = max($x1, $x2);
        $minY = min($y1, $y2);
        $maxY = max($y1, $y2);

        // Chọn một vị trí ngẫu nhiên trong hình vuông
        $spawnX = mt_rand($minX, $maxX);
        $spawnY = mt_rand($minY, $maxY);
        $spawnZ = $world->getHighestBlockAt($spawnX, $spawnY);

        // Tạo một vị trí mới từ dữ liệu config
        $location = new Location($spawnX, $spawnY, $spawnZ, $world, 0, 0);

        // Spawn entity
        $entity = new $mobName($location);


        $entity->spawnToAll();
    }

    // Sử dụng spawnEntityInSquareFromConfig trong onRun
    public function onRun(int $currentTick = -1) : void {
        $configFilePath = "path/to/your/config.yml"; // Đường dẫn tới tệp cấu hình
        $world = $this->plugin->getServer()->getDefaultLevel();
        $this->spawnEntityInSquareFromConfig("Zombie", $world, $configFilePath);
    }
}

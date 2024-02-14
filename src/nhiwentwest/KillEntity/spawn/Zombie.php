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
        $this->plugin->getServer()->getWorldManager()->getWorlds() as $world;
    }

    public function spawnEntityInSquareFromConfig(string $mobName, World $world, string $configFilePath) {
        
        // Lấy dữ liệu từ tệp cấu hình
        $mobName = Zombie;
        $world = $server->getLevelByName($worlds);
        $configData = yaml_parse_file($configFilePath);
        if ($configData === false || !isset($configData['x1']) || !isset($configData['y1']) || !isset($configData['x2']) || !isset($configData['y2'])) {
            return Main::$instance->getServer()->getLogger()->info("§cError§f loading or invalid config file §d$configFilePath §r");
        }
// Lấy toạ độ từ config
$xCoords = explode(", ", $configData['x']);
$yCoords = explode(", ", $configData['y']);

// Kiểm tra độ dài của mảng để đảm bảo chúng có đủ toạ độ
if (count($xCoords) < 2 || count($yCoords) < 2) {
    return Main::$instance->getServer()->getLogger()->info("§cError§f invalid coordinates in config file §d$configFilePath §r");
}

// Xác định các giới hạn của hình vuông
$minX = min($xCoords);
$maxX = max($xCoords);
$minY = min($yCoords);
$maxY = max($yCoords);

// Chọn một toạ độ ngẫu nhiên trong khu vực hình vuông
$spawnX = mt_rand($minX, $maxX);
$spawnY = mt_rand($minY, $maxY);
$spawnZ = 0; // Sẽ cần phải thay đổi nếu bạn muốn sử dụng toạ độ z từ config

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

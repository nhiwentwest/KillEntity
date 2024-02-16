<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity;

use pmmp\TesterPlugin\TestFailedException;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Utils;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use cooldogedev\BedrockEconomy\libs\cooldogedev\libSQL\context\ClosureContext;
use InvalidArgumentException;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\permission\DefaultPermissions;
use nhiwentwest\KillEntity\economy\EconomyIntegration;
use nhiwentwest\KillEntity\economy\EconomyManager;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\entity\Zombie;
use pocketmine\entity\Location;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener {
    
    public $myConfig;
    public static $instance; 

	
    public function onEnable(): void {
        self::$instance = $this;
        EconomyManager::init($this);
   
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->myConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
     
      
        }


	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if ($sender instanceof Player and !$sender->hasPermission(DefaultPermissions::ROOT_OPERATOR)) {
			$sender->sendMessage("§cYou do not have permission to use this commands§r");
			return true;
		}
if ($label === "zombie") {
$x = $this->getConfig()->get("x");
    $y = $this->getConfig()->get("y");
    $z = $this->getConfig()->get("z");

    // Kiểm tra xem các giá trị đã được đọc thành công chưa
    if ($x === null || $y === null || $z === null) {
        $this->getLogger()->warning("Không thể đọc tọa độ từ file cấu hình.");
        return false;
    }

    // Lấy thế giới mặc định
	
$worldName = "world"; // Thay "your_world_name" bằng tên thế giới của bạn
$worldManager = Server::getInstance()->getWorldManager();
$world = $worldManager->getWorldByName($worldName);
   $pos = new Vector3($x, $y, $z);
  
    $zombie = new Zombie($pos, $world);

    // Gửi đối tượng Zombie tới tất cả người chơi trong thế giới
    $zombie->spawnToAll();
return true;
}
	}
	


public function spawnCustomZombie() : void {
    // Đọc tọa độ từ file cấu hình
    $x = $this->getConfig()->get("x");
    $y = $this->getConfig()->get("y");
    $z = $this->getConfig()->get("z");

    // Kiểm tra xem các giá trị đã được đọc thành công chưa
    if ($x === null || $y === null || $z === null) {
        $this->getLogger()->warning("Không thể đọc tọa độ từ file cấu hình.");
        return;
    }

    // Lấy thế giới mặc định
	
$worldName = "world"; // Thay "your_world_name" bằng tên thế giới của bạn
$worldManager = Server::getInstance()->getWorldManager();
$world = $worldManager->getWorldByName($worldName);
    // Kiểm tra xem thế giới có tồn tại không
   

    // Tạo đối tượng Vector3 từ tọa độ đã đọc
    $pos = new Vector3($x, $y, $z);
  
    $zombie = new Zombie($pos, $world);

    // Gửi đối tượng Zombie tới tất cả người chơi trong thế giới
    $zombie->spawnToAll();
}

 
        public function onEntityDeath(EntityDeathEvent $event): void {
            
    
        $killedEntity = $event->getEntity();
        $cause = $killedEntity->getLastDamageCause();
   
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
   
         
            if ($damager instanceof Player) {
                if ($damager->hasPermission("killentity.plugin")) {
                $levelName = $damager->getWorld()->getFolderName();
                
                      
                if(in_array($levelName, $this->getConfig()->get("worlds"))){
                $allowedEntityTypes = $this->getConfig()->get("animals");
	
             
                foreach ($allowedEntityTypes as $index => $entityData) {
                    $entityType = key($entityData);
                    $moneyReward = current($entityData);
            
              
                    
                    
                    if ($killedEntity->getName() === $entityType) {
                     
                       
                            $playerName = $damager->getName();
                      
                        $economy = EconomyManager::get();
                        $economy->addMoney($damager, $moneyReward);
                        
                        
                        $msg = $this->getConfig()->get("message");
                              

                               if ($msg === 1) {
                                
                                   $customMessage = TextFormat::GREEN . "+" . TextFormat::YELLOW . "$" . $moneyReward;
                                   $damager->sendPopup($customMessage);
                               } elseif ($msg === 2) {
                                  
                                   $customMessage = TextFormat::GREEN . "+" . TextFormat::YELLOW . "$" . $moneyReward;
                                   $damager->sendMessage($customMessage);
                                   
                               }
                               
                        elseif ($msg === 3) {
                            continue;
                        }
                               else {
                                   // Default case: 'message' is not set or has an invalid value
                                   $this->getLogger()->info("Invalid value for 'message' in the config.");
                               }

                          
                        }
                        }
                    }

			    else {
Server::getInstance()->getLogger()->info("KillEntity: The world is not activated");
		    
	    }

			
                }

		        else {
 $damager->sendMessage("You don't have permission to earn coin.");
		    
	    }
		    
            }
        }
    }
    
    
    public function onPlayerDeath(PlayerDeathEvent $event): void {
          $player = $event->getPlayer();
          $cause = $player->getLastDamageCause();
        
        $playerName = $player->getName();
        
     

        if ($player->hasPermission("killentity.plugin")) {
            
        if ($cause instanceof EntityDamageByEntityEvent) {
            
            $damager = $cause->getDamager();
           
           
            
        
            $lostPercentage = $this->getConfig()->get("percent");
            
        
            $levelName = $player->getWorld()->getFolderName();
            
                  
            if(in_array($levelName, $this->getConfig()->get("worlds"))){
            $allowedEntityTypes = $this->getConfig()->get("animals");
         
            foreach ($allowedEntityTypes as $index => $entityData) {
                $entityType = key($entityData);
 
                  $economy = EconomyManager::get();
                    
                    $msg = $this->getConfig()->get("message");
                          
              $economy->getMoney($player, static function(float $money) use($player, $lostPercentage, $msg) : void {
                               
                                 $currentBalance = $money;

                                 $amountToDeduct = (int) ceil($currentBalance * ($lostPercentage / 100));
                                 
                                 $economy = EconomyManager::get();
                                 $economy->removeMoney($player, $amountToDeduct);
                                 
                                 
                                 if ($msg === 2) {
                                  
                                     $customMessage = TextFormat::RED . "-" . TextFormat::YELLOW . "$" . $amountToDeduct;
                                 $player->sendMessage($customMessage);
                                 }
                                 
                                 
              });
 
                }
                }
	    else {
Server::getInstance()->getLogger()->info("KillEntity: The world is not activated");
		    
	    }
                }
            
              
            
            }
        
                   else {
 $player->sendMessage("You don't have permission to earn coin.");
		    
	    }

    
    }





    
   }




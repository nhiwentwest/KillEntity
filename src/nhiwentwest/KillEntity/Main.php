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
use pocketmine\console\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\world\World;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use nhiwentwest\KillEntity\economy\EconomyIntegration;
use nhiwentwest\KillEntity\economy\EconomyManager;
use pocketmine\event\player\PlayerDeathEvent;



class Main extends PluginBase implements Listener {
    
    public $myConfig;
    public static $instance; 
    public $spawnobj;
    public function onEnable(): void {
        self::$instance = $this;
        EconomyManager::init($this);
        $this->spawnobj = (new Spawn);
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->myConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
     
      
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
                }
            
              
            
            }
        
           

    
    }

    	public function spawnEntity(string $mobname, World $world, Vector3 $pos) {
		$location = new Location($pos->x, $pos->y, $pos->z, $world, 0, 0);

		if (Main::$instance->attrobj->isFlying($mobname)) {
			$location = new Location($pos->x, $pos->y+8, $pos->z, $world, 0, 0);
		}

		$entity = new Main::$instance->classes[$mobname]($location);

		if ($entity == null) {
			return Main::$instance->getServer()->getLogger()->info("§cError§f spawning mob §d$mobname §r");
		}

		$entity->spawnToAll();
	}


    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
    if ($label === "zombie") {
        // Kiểm tra xem lệnh được gửi từ player hay từ console
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThat command cannot be done from the console§r");
            return true;
        }

        // Gọi phương thức spawnEntity() trong đối tượng $this->spawnobj để spawn một con zombie
        $this->spawnobj->spawnEntity("Zombie", $sender->getWorld(), $sender->getPosition());
        return true;
    }

    return false;
}

    
   }




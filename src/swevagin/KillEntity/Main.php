<?php

declare(strict_types=1);

namespace swevagin\KillEntity;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\Level;
use pocketmine\world\World;



    

class Main extends PluginBase implements Listener {
    
    public $myConfig;
    public function onEnable(): void {
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
                      
                            $command = "addbalance $playerName $moneyReward";
                       

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

                            $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), $command);
                        }
                        }
                    }
                }
            }
        }
    }
}

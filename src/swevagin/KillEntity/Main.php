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




class Main extends PluginBase implements Listener {
    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

           }
    

    public function onEntityDeath(EntityDeathEvent $event): void {
        $killedEntity = $event->getEntity();
        $cause = $killedEntity->getLastDamageCause();

        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();

            if ($damager instanceof Player) {
                // Check if the damager is a Player
                $allowedEntityTypes = ["Zombie", "Villager", "Cow"];

                if (in_array($killedEntity->getName(), $allowedEntityTypes)) {
                    $moneyReward = $this->getConfig()->get("amount");


                    if ($moneyReward > 0) {
                        // Use BedrockEconomy to add balance to the player
                        $playerName = $damager->getName();
                        $command = "addbalance $playerName $moneyReward";
                        // Log debug messages to the console
                  
                        
                  
                        $customMessage = TextFormat::GREEN . "+" . TextFormat::YELLOW . "$" . $moneyReward;
                        $damager->sendPopup($customMessage);
                        
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage()), $command);

              

                        
                    }

                }
                
                
                else {
                    // Send a message if the entity is not an allowed type
                    $damager->sendMessage("The entity is not an allowed type.");
                }
                
        
            }
        }
    }
}

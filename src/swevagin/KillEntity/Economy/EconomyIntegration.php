<?php

declare(strict_types=1);

namespace swevagin\KillEntity\economy;

use Closure;
use pocketmine\player\Player;
# AlexPads credit
interface EconomyIntegration{

	public function init(array $config) : void;

	public function getMoney(Player $player, Closure $callback) : void;

	public function addMoney(Player $player, float $money) : void;

	
	public function removeMoney(Player $player, float $money) : void;

	
	public function formatMoney(float $money) : string;
}

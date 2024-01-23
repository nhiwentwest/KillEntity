<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity\economy;

use onebone\economyapi\EconomyAPI;
use onebone\economyapi\currency\Currency;
use Closure;
use pocketmine\player\Player;


final class EconomyAPIIntegration implements EconomyIntegration{

	private EconomyAPI $plugin;

	public function __construct(){
		$this->plugin = EconomyAPI::getInstance();
	}

	public function init(array $config) : void{
	}

	public function getMoney(Player $player, Closure $callback) : void{
		$money = $this->plugin->myMoney($player->getName());
		assert(is_float($money));
		$callback($money);
	}
	 

		public function getMonetaryUnit(): string {
	        return $this->getDefaultCurrency->getCurrency()->getSymbol();
	}

	public function addMoney(Player $player, float $money) : void{
		$this->plugin->addMoney($player->getName(), $money);
	}

	public function removeMoney(Player $player, float $money) : void{
		$this->plugin->reduceMoney($player->getName(), $money);
	}

	public function formatMoney(float $money) : string{
		return $this->plugin->getMonetaryUnit() . number_format($money);
	}
}

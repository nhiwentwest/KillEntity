<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity\economy;

use Closure;
use InvalidArgumentException;
use pocketmine\player\Player;
use pocketmine\Server;

class EconomyAPIIntegration {

    private EconomyAPI $plugin;

    public function __construct() {
        $this->plugin = EconomyAPI::getInstance();
    }

    public function init(array $config): void {
    }

    public function getMoney(Player $player, Closure $callback): void {
        $money = $this->plugin->myMoney($player->getName());
        assert(is_float($money));
        $callback($money);
    }

    public function addMoney(Player $player, float $money): void {
        $this->plugin->addMoney($player->getName(), $money);
    }

    public function removeMoney(Player $player, float $money): void {
        $this->plugin->reduceMoney($player->getName(), $money);
    }

    public function formatMoney(float $money): string {
        return $this->plugin->getMonetaryUnit() . number_format($money);
    }
}

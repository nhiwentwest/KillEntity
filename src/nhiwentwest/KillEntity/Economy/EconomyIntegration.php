<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity\economy;

use Closure;
use InvalidArgumentException;
use pocketmine\player\Player;
use pocketmine\Server;

interface EconomyIntegration {

    public function init(array $config): void;

    public function getMoney(Player $player, Closure $callback): void;

    public function addMoney(Player $player, float $money): void;

    public function removeMoney(Player $player, float $money): void;

    public function formatMoney(float $money): string;
}

class BedrockEconomyIntegration implements EconomyIntegration {

    private BedrockEconomy $plugin;

    public function __construct() {
        /** @var BedrockEconomy|null $plugin */
        $plugin = Server::getInstance()->getPluginManager()->getPlugin("BedrockEconomy");
        if ($plugin === null) {
            throw new InvalidArgumentException("BedrockEconomy plugin was not found");
        }

        $this->plugin = $plugin;
    }

    public function init(array $config): void {
    }

    public function getMoney(Player $player, Closure $callback): void {
        BedrockEconomyAPI::getInstance()->getPlayerBalance($player->getName(), ClosureContext::create(static function (?int $balance) use ($callback): void {
            $callback($balance ?? 0);
        }));
    }

    public function addMoney(Player $player, float $money): void {
        BedrockEconomyAPI::getInstance()->addToPlayerBalance($player->getName(), (int)ceil($money));
    }

    public function removeMoney(Player $player, float $money): void {
        BedrockEconomyAPI::getInstance()->subtractFromPlayerBalance($player->getName(), (int)ceil($money));
    }

    public function formatMoney(float $money): string {
        return $this->plugin->getCurrencyManager()->getSymbol() . number_format($money);
    }
}

class EconomyAPIIntegration implements EconomyIntegration {

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

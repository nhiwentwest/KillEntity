<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity\Economy;

use Closure;
use InvalidArgumentException;
use pocketmine\player\Player;
use pocketmine\Server;

class BedrockEconomyIntegration {

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
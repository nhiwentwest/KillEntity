<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity\Entities;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Zombie extends MobsEntity {
	const TYPE_ID = EntityIds::ZOMBIE;
	const HEIGHT = 1.95;
}

<?php

declare(strict_types=1);

namespace nhiwentwest\KillEntity\Behav;

use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use nhiwentwest\KillEntity\Entities\Zombie;


class Registrations {
	public function registerEntities() {
		Main::$instance->classes = $this->getClasses();
		$entityFactory = EntityFactory::getInstance();
		foreach (Main::$instance->classes as $entityName => $typeClass) {
			$entityFactory->register($typeClass,
				static function(World $world, CompoundTag $nbt) use($typeClass): MobsEntity {
					return new $typeClass(EntityDataHelper::parseLocation($nbt, $world), $nbt);
				},
			[$entityName]);
		}
	}

	public function getClasses() : array {
		return [

			"Zombie" => Zombie::class,
			
		];
	}
}

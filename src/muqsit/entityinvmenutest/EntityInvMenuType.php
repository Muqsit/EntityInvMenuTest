<?php

declare(strict_types=1);

namespace muqsit\entityinvmenutest;

use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\FixedInvMenuType;
use muqsit\invmenu\type\graphic\InvMenuGraphic;
use pocketmine\entity\Entity;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

final class EntityInvMenuType implements FixedInvMenuType{

	private string $entity_type;
	private int $window_type;
	private int $size;

	public function __construct(string $entity_type, int $window_type, int $size){
		$this->entity_type = $entity_type;
		$this->window_type = $window_type;
		$this->size = $size;
	}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InvMenu $menu, Player $player) : ?InvMenuGraphic{
		return new EntityInvMenuGraphic($this->entity_type, $this->window_type, $this->size, Entity::nextRuntimeId());
	}

	public function createInventory() : Inventory{
		return new InvMenuInventory($this->size);
	}
}
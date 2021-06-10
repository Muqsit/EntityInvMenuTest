<?php

declare(strict_types=1);

namespace muqsit\entityinvmenutest;

use muqsit\invmenu\type\graphic\InvMenuGraphic;
use muqsit\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use muqsit\invmenu\type\graphic\network\MultiInvMenuGraphicNetworkTranslator;
use muqsit\invmenu\type\graphic\network\WindowTypeInvMenuGraphicNetworkTranslator;
use pocketmine\inventory\Inventory;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\player\Player;

final class EntityInvMenuGraphic implements InvMenuGraphic{

	private string $entity_type;
	private int $window_type;
	private int $size;
	private int $entity_runtime_id;

	public function __construct(string $entity_type, int $window_type, int $size, int $entity_runtime_id){
		$this->entity_type = $entity_type;
		$this->window_type = $window_type;
		$this->size = $size;
		$this->entity_runtime_id = $entity_runtime_id;
	}

	public function send(Player $player, ?string $name) : void{
		$packet = new AddActorPacket();
		$packet->entityRuntimeId = $this->entity_runtime_id;
		$packet->type = $this->entity_type;
		$packet->position = $player->getPosition();

		$metadata = new EntityMetadataCollection();
		$metadata->setInt(EntityMetadataProperties::CONTAINER_TYPE, $this->window_type);
		$metadata->setInt(EntityMetadataProperties::CONTAINER_BASE_SIZE, $this->size);
		$metadata->setGenericFlag(EntityMetadataFlags::CHESTED, true); // not generic to entities?
		$packet->metadata = $metadata->getAll();

		$player->getNetworkSession()->sendDataPacket($packet);
	}

	public function remove(Player $player) : void{
		$player->getNetworkSession()->sendDataPacket(RemoveActorPacket::create($this->entity_runtime_id));
	}

	public function sendInventory(Player $player, Inventory $inventory) : bool{
		return $player->setCurrentWindow($inventory);
	}

	public function getNetworkTranslator() : ?InvMenuGraphicNetworkTranslator{
		return new MultiInvMenuGraphicNetworkTranslator([
			new WindowTypeInvMenuGraphicNetworkTranslator($this->window_type),
			new EntityInvMenuGraphicNetworkTranslator($this->entity_runtime_id)
		]);
	}
}
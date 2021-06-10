<?php

declare(strict_types=1);

namespace muqsit\entityinvmenutest;

use muqsit\invmenu\session\InvMenuInfo;
use muqsit\invmenu\session\PlayerSession;
use muqsit\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

final class EntityInvMenuGraphicNetworkTranslator implements InvMenuGraphicNetworkTranslator{

	private int $entity_runtime_id;

	public function __construct(int $entity_runtime_id){
		$this->entity_runtime_id = $entity_runtime_id;
	}

	public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet) : void{
		$packet->entityUniqueId = $this->entity_runtime_id;
		$packet->x = 0;
		$packet->y = 0;
		$packet->z = 0;
	}
}
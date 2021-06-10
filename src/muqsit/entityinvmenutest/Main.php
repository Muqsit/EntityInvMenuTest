<?php

declare(strict_types=1);

namespace muqsit\entityinvmenutest;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

final class Main extends PluginBase{

	private const TYPE_LLAMA_PREFIX = "entityinvmenutest:llama_";

	public static function createLLamaMenu(int $size) : InvMenu{
		static $registered_sizes = [];
		if(!isset($registered_sizes[$size])){
			$registered_sizes[$size] = self::TYPE_LLAMA_PREFIX . $size;
			InvMenuHandler::getTypeRegistry()->register($registered_sizes[$size], new EntityInvMenuType(EntityIds::LLAMA, WindowTypes::HORSE, $size));
		}

		return InvMenu::create($registered_sizes[$size]);
	}

	protected function onEnable() : void{
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(!($sender instanceof Player)){
			return true;
		}

		self::createLLamaMenu((int) ($args[0] ?? 15))->send($sender);
		return true;
	}
}
<?php
declare(strict_types=1);
namespace jasonwynn10\EasyCommandAutofill;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandEnumConstraint;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
	/** @var CommandData[] $manualOverrides */
	protected array $manualOverrides = [];
	/** @var string[] $debugCommands */
	protected array $debugCommands = [];
	/** @var CommandEnum[] $hardcodedEnums */
	protected array $hardcodedEnums = [];
	/** @var CommandEnum[] $softEnums */
	protected array $softEnums = [];
	/** @var CommandEnumConstraint[] $enumConstraints */
	protected array $enumConstraints = [];

	public function onEnable() {
		new EventListener($this);
		if($this->getConfig()->get("Highlight-Debug", true))
			$this->debugCommands = ["dumpmemory", "gc", "timings", "status"];
		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "new difficulty";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
		$param->isOptional = false;
		$data->overloads[0] = [$param];
		$this->addManualOverride("difficulty", $data);

		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "player";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[0] = [$param];
		$param = new CommandParameter();
		$param->paramName = "item";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
		$param->isOptional = false;
		$data->overloads[0][] = $param;
		$param = new CommandParameter();
		$param->paramName = "amount";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_INT;
		$param->isOptional = true;
		$data->overloads[0][] = $param;
		$param = new CommandParameter();
		$param->paramName = "tags";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_JSON;
		$param->isOptional = true;
		$data->overloads[0][] = $param;
		$this->addManualOverride("give", $data);

		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "position";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_POSITION;
		$param->isOptional = true;
		$data->overloads[0] = [$param];
		$this->addManualOverride("setworldspawn", $data);

		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "player";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = true;
		$data->overloads[0] = [$param];
		$param = new CommandParameter();
		$param->paramName = "position";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_POSITION;
		$param->isOptional = true;
		$data->overloads[0][] = $param;
		$this->addManualOverride("spawnpoint", $data);

		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "victim";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[0] = [$param];
		$param = new CommandParameter();
		$param->paramName = "destination";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = true;
		$data->overloads[0][] = $param;
		$param = new CommandParameter();
		$param->paramName = "victim";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[1] = [$param];
		$param = new CommandParameter();
		$param->paramName = "destination";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_POSITION;
		$param->isOptional = true;
		$data->overloads[1][] = $param;
		$param = new CommandParameter();
		$param->paramName = "x-rot";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_FLOAT;
		$param->isOptional = true;
		$data->overloads[1][] = $param;
		$param = new CommandParameter();
		$param->paramName = "y-rot";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_FLOAT;
		$param->isOptional = true;
		$data->overloads[1][] = $param;
		$this->addManualOverride("teleport", $data);

		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "player";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[0] = [$param];
		$param = new CommandParameter();
		$param->paramName = "title Enum #0";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM | 0;
		$param->isOptional = false;
		$param->enum = new CommandEnum();
		$param->enum->enumName = "title Enum #0";
		$param->enum->enumValues = ["clear"];
		$data->overloads[0][] = $param;
		$param = new CommandParameter();
		$param->paramName = "player";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[1] = [$param];
		$param = new CommandParameter();
		$param->paramName = "title Enum #1";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM | 1;
		$param->isOptional = false;
		$param->enum = new CommandEnum();
		$param->enum->enumName = "title Enum #1";
		$param->enum->enumValues = ["reset"];
		$data->overloads[1][] = $param;
		$param = new CommandParameter();
		$param->paramName = "player";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[2] = [$param];
		$param = new CommandParameter();
		$param->paramName = "title Enum #2";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM | 2;
		$param->isOptional = false;
		$param->enum = new CommandEnum();
		$param->enum->enumName = "title Enum #2";
		$param->enum->enumValues = ["title", "subtitle", "actionbar"];
		$data->overloads[2][] = $param;
		$param = new CommandParameter();
		$param->paramName = "titleText";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_MESSAGE;
		$param->isOptional = false;
		$data->overloads[2][] = $param;
		$param = new CommandParameter();
		$param->paramName = "player";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_TARGET;
		$param->isOptional = false;
		$data->overloads[3] = [$param];
		$param = new CommandParameter();
		$param->paramName = "title Enum #3";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM | 3;
		$param->isOptional = false;
		$param->enum = new CommandEnum();
		$param->enum->enumName = "title Enum #3";
		$param->enum->enumValues = ["times"];
		$data->overloads[3][] = $param;
		$param = new CommandParameter();
		$param->paramName = "fadeIn";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_INT;
		$param->isOptional = false;
		$data->overloads[3][] = $param;
		$param = new CommandParameter();
		$param->paramName = "stay";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_INT;
		$param->isOptional = false;
		$data->overloads[3][] = $param;
		$param = new CommandParameter();
		$param->paramName = "fadeOut";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_INT;
		$param->isOptional = false;
		$data->overloads[3][] = $param;
		$this->addManualOverride("title", $data);

		$data = new CommandData();
		$param = new CommandParameter();
		$param->paramName = "plugin name";
		$param->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
		$param->isOptional = true;
		$data->overloads[0] = [$param];
		$this->addManualOverride("version", $data);
	}

	/**
	 * @param string $commandName
	 * @param CommandData $data
	 *
	 * @return self
	 */
	public function addManualOverride(string $commandName, CommandData $data) : self {
		$this->manualOverrides[$commandName] = $data;
		foreach($this->getServer()->getOnlinePlayers() as $player) {
			$player->sendDataPacket(new AvailableCommandsPacket());
		}
		return $this;
	}

	/**
	 * @return CommandData[]
	 */
	public function getManualOverrides() : array {
		return $this->manualOverrides;
	}

	/**
	 * @param string $commandName
	 *
	 * @return self
	 */
	public function addDebugCommand(string $commandName) : self {
		$this->debugCommands[] = $commandName;
		foreach($this->getServer()->getOnlinePlayers() as $player) {
			$player->sendDataPacket(new AvailableCommandsPacket());
		}
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getDebugCommands() : array {
		return $this->debugCommands;
	}

	/**
	 * @param CommandEnum $enum
	 *
	 * @return self
	 */
	public function addHardcodedEnum(CommandEnum $enum) : self {
		foreach($this->softEnums as $softEnum)
			if($enum->enumName === $softEnum->enumName)
				throw new \InvalidArgumentException("Hardcoded enum is already in soft enum list.");
		$this->hardcodedEnums[] = $enum;
		foreach($this->getServer()->getOnlinePlayers() as $player) {
			$player->sendDataPacket(new AvailableCommandsPacket());
		}
		return $this;
	}

	/**
	 * @return CommandEnum[]
	 */
	public function getHardcodedEnums() : array {
		return $this->hardcodedEnums;
	}

	/**
	 * @param CommandEnum $enum
	 *
	 * @return self
	 */
	public function addSoftEnum(CommandEnum $enum) : self {
		foreach($this->hardcodedEnums as $hardcodedEnum)
			if($enum->enumName === $hardcodedEnum->enumName)
				throw new \InvalidArgumentException("Soft enum is already in hardcoded enum list.");
		$this->softEnums[] = $enum;
		$pk = new UpdateSoftEnumPacket();
		$pk->enumName = $enum->enumName;
		$pk->values = $enum->enumValues;
		$pk->type = UpdateSoftEnumPacket::TYPE_ADD;
		$this->getServer()->broadcastPacket($this->getServer()->getOnlinePlayers(), $pk);
		return $this;
	}

	/**
	 * @return CommandEnum[]
	 */
	public function getSoftEnums() : array {
		return $this->softEnums;
	}

	/**
	 * @param CommandEnum $enum
	 *
	 * @return self
	 */
	public function updateSoftEnum(CommandEnum $enum) : self {
		$pk = new UpdateSoftEnumPacket();
		$pk->enumName = $enum->enumName;
		$pk->values = $enum->enumValues;
		$pk->type = UpdateSoftEnumPacket::TYPE_SET;
		$this->getServer()->broadcastPacket($this->getServer()->getOnlinePlayers(), $pk);
		return $this;
	}

	/**
	 * @param CommandEnum $enum
	 *
	 * @return self
	 */
	public function removeSoftEnum(CommandEnum $enum) : self {
		$pk = new UpdateSoftEnumPacket();
		$pk->enumName = $enum->enumName;
		$pk->values = $enum->enumValues;
		$pk->type = UpdateSoftEnumPacket::TYPE_REMOVE;
		$this->getServer()->broadcastPacket($this->getServer()->getOnlinePlayers(), $pk);
		return $this;
	}

	/**
	 * @param CommandEnumConstraint $enumConstraint
	 *
	 * @return Main
	 */
	public function addEnumConstraint(CommandEnumConstraint $enumConstraint) : self {
		foreach($this->hardcodedEnums as $hardcodedEnum)
			if($enumConstraint->getEnum()->enumName === $hardcodedEnum->enumName) {
				$this->enumConstraints[] = $enumConstraint;
				foreach($this->getServer()->getOnlinePlayers() as $player) {
					$player->sendDataPacket(new AvailableCommandsPacket());
				}
				return $this;
			}
		foreach($this->softEnums as $softEnum)
			if($enumConstraint->getEnum()->enumName === $softEnum->enumName) {
				$this->enumConstraints[] = $enumConstraint;
				foreach($this->getServer()->getOnlinePlayers() as $player) {
					$player->sendDataPacket(new AvailableCommandsPacket());
				}
				return $this;
			}
		throw new \InvalidArgumentException("Enum name does not exist in any Enum list");
	}

	/**
	 * @return CommandEnumConstraint[]
	 */
	public function getEnumConstraints() : array {
		return $this->enumConstraints;
	}
}
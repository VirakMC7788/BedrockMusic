<?php

namespace benjamin\BedrockMusic\commands;

use benjamin\BedrockMusic\BedrockMusic;
use benjamin\BedrockMusic\forms\MusicForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class BedrockMusicCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {

        $this->setPermission("bedrockmusic.command");

        parent::__construct($name, $description, $usageMessage, $aliases);

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {

        if (!$sender instanceof Player) return;

        if (!$sender->hasPermission("bedrockmusic.commad")) {

            $sender->sendMessage(BedrockMusic::$prefix . "You don't have permissions to use this command");
            return;

        }

        if (isset($args[0])) {

            $config = new Config(BedrockMusic::getDirection() . "config.yml", Config::YAML);

            if (strtolower($args[0]) === "radius") {

                if (isset($args[1]) && is_numeric($args[1])) {

                    BedrockMusic::$radius = $args[1];
                    BedrockMusic::$position = $sender->asVector3();

                    $config->set("onlyradius", true);
                    $config->set("radius", $args[1]);

                    $config->save();

                    $sender->sendMessage(BedrockMusic::$prefix . "Radius saved");

                } else $sender->sendMessage(BedrockMusic::$prefix . "/bedrockmusic radius <number>");

                return;

            } elseif (strtolower($args[0]) === "position") {

                BedrockMusic::$radius_play = true;
                BedrockMusic::$position = $sender->asVector3();

                $config->set("onlyradius", true);
                $config->set("position", $sender->getX() . ":" . $sender->getY() . ":" . $sender->getZ());

                $config->save();

                $sender->sendMessage(BedrockMusic::$prefix . "Position saved");

                return;

            }

            $sender->sendMessage(BedrockMusic::$prefix . "/bedrockmusic <radius / position> [number]");

            return;
        }

        $sender->sendForm(new MusicForm());
    }

}
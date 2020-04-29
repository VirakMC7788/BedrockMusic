<?php

namespace benjamin\BedrockMusic\forms;

use benjamin\BedrockMusic\BedrockMusic;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\item\Bed;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SongForm extends SimpleForm {

    public function __construct() {
        $callable = function (Player $player, $data) {
            if ($data === null) return;
            $songs = array_merge(BedrockMusic::getAllSongs());
            if (count($songs) == 0) return;
            $song = $songs[$data];
            $player->sendMessage(BedrockMusic::$prefix . "Playing " . TextFormat::YELLOW . str_replace(".nbs", "", $song));
            BedrockMusic::startSong($song);
        };
        parent::__construct($callable);
        $this->setTitle("Select song");
        $songs = array_merge(BedrockMusic::getAllSongs());
        $images = ["textures/items/record_ward", "textures/items/record_wait","textures/items/record_strad", "textures/items/record_stal", "textures/items/record_blocks", "textures/items/record_cat", "textures/items/record_13"];
        if (count($songs) == 0) {
            $this->addButton(TextFormat::RED . "No songs available");
            return;
        }
        foreach ($songs as $song) {
            $this->addButton(str_replace(".nbs", "", $song), 0, $images[mt_rand(0, count($images)-1)]);
        }
    }

}
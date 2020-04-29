<?php

namespace benjamin\BedrockMusic\forms;

use benjamin\BedrockMusic\BedrockMusic;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class MusicForm extends SimpleForm {

    public function __construct() {

        $callable = function (Player $player, $data) {
            if ($data === null) return;
            if ($data == 0) {
                $player->sendMessage(BedrockMusic::$prefix . "Music stopped");
                BedrockMusic::getInstance()->getScheduler()->cancelAllTasks();
            } elseif ($data == 1) {
                $player->sendForm(new SongForm());
            }
        };

        parent::__construct($callable);

        $this->setTitle("BedrockMusic");

        $this->addButton("Stop music");
        $this->addButton("Play song");
    }

}
<?php

namespace benjamin\BedrockMusic\tasks;

use benjamin\BedrockMusic\BedrockMusic;
use pocketmine\scheduler\Task;

class MusicPlayTask extends Task {

    public function onRun(int $currentTick) {
        if (!BedrockMusic::$is_playing) return;
        if(isset(BedrockMusic::$song->sounds[BedrockMusic::$song->tick])){
            $i = 0;
            foreach(BedrockMusic::$song->sounds[BedrockMusic::$song->tick] as $data){
                BedrockMusic::playSound($data[0], $data[1]);
                $i++;
            }
        }
        BedrockMusic::$song->tick++;
        if(BedrockMusic::$song->tick > BedrockMusic::$song->length){
            BedrockMusic::stopSong();
        }
    }

}
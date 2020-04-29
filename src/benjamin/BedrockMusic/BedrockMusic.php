<?php

namespace benjamin\BedrockMusic;

use benjamin\BedrockMusic\commands\BedrockMusicCommand;
use benjamin\BedrockMusic\tasks\MusicPlayTask;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class BedrockMusic extends PluginBase {

    /**
     *
     *  The API isn't mine -> https://github.com/BrokenItZAndrei100/ZMusicBox
     *  I fixed all errors and changed it to another possibility to play music
     *
    **/

    public static $instance;

    public static $prefix = TextFormat::YELLOW . "BedrockMusic " . TextFormat::DARK_GRAY . "» " . TextFormat::GRAY;

    public static $radius_play = false, $radius = 12, $position = null;

    public static $is_playing = false, $song, $songname = "", $playtask;

    public function onEnable() {

        $this->saveResource("config.yml", false);

        $this->getServer()->getCommandMap()->register("bedrockmusic", new BedrockMusicCommand("bedrockmusic", "Plays music", null, ["playmusic"]));
        self::$instance = $this;

        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        if ($config->exists("onlyradius") && is_bool($config->get("onlyradius"))) {
            self::$radius_play = $config->get("onlyradius");
        }

        if ($config->exists("radius") && is_numeric($config->get("radius"))) {
            self::$radius = $config->get("radius");
        }

        if ($config->exists("position") && is_string($config->get("position"))) {
            $position = $config->get("position");
            $position = explode(":", $position);
            if (is_array($position)) {
                $position = new Vector3($position[0], $position[1], $position[2]);
                self::$position = $position;
            }

        }

    }

    public static function getInstance() : self {

        return self::$instance;

    }

    public static function getDirection() {

        return self::getInstance()->getDataFolder();

    }

    public static function isOnlyArea() : bool {

        return self::$radius_play;

    }

    public static function isInArea(Player $player) : bool {

        if (!self::$radius_play) return true;

        if (!self::$position instanceof Vector3) return false;

        if ($player->distance(self::$position) <= self::$radius) return true;


        return false;

    }

    public static function getAllSongs() : array {

        $dir = self::getDirection();
        $scan = scandir($dir);

        $songs = [];

        foreach ($scan as $key => $file) {

            if (!in_array($file, array(".", ".."))) {

                if (!is_dir($dir . DIRECTORY_SEPARATOR . $file)) {

                    $ex = explode('.', $file);

                    if ($ex[1] === "nbs") {

                        $songs[] = $file;

                    }

                }

            }

        }

        return $songs;

    }

    public static function startSong(string $song) {

        self:self::stopSong();

        $dir = self::getDirection() . $song;
        $api = new MusicAPI($dir);

        self::$songname = str_replace(".nbs", "", $song);
        self::$song = $api;
        self::$is_playing = true;
        self::$playtask = new MusicPlayTask();

        if (self::$song->speed !== 0) {

            self::getInstance()->getScheduler()->scheduleRepeatingTask(self::$playtask, 2990 / self::$song->speed);

        } else {

            self::getInstance()->getScheduler()->scheduleRepeatingTask(self::$playtask, 2990);

        }

    }

    public static function stopSong() {

        self::$song = null;
        self::$is_playing = false;
        self::$playtask = null;

        self::getInstance()->getScheduler()->cancelAllTasks();

    }

    public static function playSound($sound, $type = 0) {

        if(is_numeric($sound) and $sound > 0) {

            $players = [];

            foreach (self::getInstance()->getServer()->getOnlinePlayers() as $player) {

                if (self::isOnlyArea() && self::isInArea($player)) {

                    $players[] = $player;

                } elseif (!self::isOnlyArea()) {

                    $players[] = $player;

                }

            }

            foreach ($players as $player) {

                $player->addActionBarMessage(TextFormat::GRAY . "Current song: " . TextFormat::YELLOW . self::$songname);

                $packet = new LevelSoundEventPacket();
                $packet->position = $player->asVector3();
                $packet->sound = LevelSoundEventPacket::SOUND_NOTE;
                $packet->extraData = $type + $sound;

                $player->sendDataPacket($packet);

            }
        }
    }

}
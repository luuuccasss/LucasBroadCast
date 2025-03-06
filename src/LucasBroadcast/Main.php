<?php

namespace LucasBroadcast;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;
use pocketmine\world\sound\Sound;
use LucasBroadcast\Commands\BroadcastCommand;

class Main extends PluginBase {


    private $config;
    private $messages = [];
    const PREFIX = "§3[§eLucasBroadCast§3]";

    public function onEnable(): void {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            "messages" => []
        ]);
        $this->messages = $this->config->get("messages", []);
        $this->scheduleBroadcasts();
        $this->getServer()->getCommandMap()->register("lucasbroadcast", new BroadcastCommand($this));
    }

    private function scheduleBroadcasts(): void {
        foreach ($this->messages as $id => $data) {
            $time = $data["time"];
            $this->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(function() use ($data): void {
                $message = $data["message"];
                $worldName = $data["world"] ?? "all";
                $usePopup = $data["popup"] ?? false;
                $soundClass = $data["sound"] ?? "none";

                foreach ($this->getServer()->getOnlinePlayers() as $player) {
                    if ($worldName === "all" || $player->getWorld()->getFolderName() === $worldName) {
                        if ($usePopup) {
                            $player->sendPopup($message);
                        } else {
                            $player->sendMessage($message);
                        }
                        if ($soundClass !== "none") {
                            $sound = $this->getSoundByClass($soundClass);
                            if ($sound !== null) {
                                $player->getWorld()->addSound($player->getPosition(), $sound);
                            }
                        }
                    }
                }
            }), $time * 20, $time * 20);
        }
    }
    private function getSoundByClass(string $class): ?Sound {
        $fullClass = "pocketmine\\world\\sound\\" . $class;
        if (class_exists($fullClass)) {
            return new $fullClass();
        }
        return null;
    }

    public function addMessage(string $message, int $time, string $world, bool $usePopup, string $soundClass): void {
        $id = "message" . (count($this->messages) + 1);
        $this->messages[$id] = [
            "time" => $time,
            "message" => $message,
            "world" => $world,
            "popup" => $usePopup,
            "sound" => $soundClass
        ];
        $this->config->set("messages", $this->messages);
        $this->config->save();

        // Planifier le nouveau message
        $this->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(function() use ($message, $world, $usePopup, $soundClass): void {
            foreach ($this->getServer()->getOnlinePlayers() as $player) {
                if ($world === "all" || $player->getWorld()->getFolderName() === $world) {
                    if ($usePopup) {
                        $player->sendPopup($message);
                    } else {
                        $player->sendMessage($message);
                    }
                    if ($soundClass !== "none") {
                        $sound = $this->getSoundByClass($soundClass);
                        if ($sound !== null) {
                            $player->getWorld()->addSound($player->getPosition(), $sound);
                        }
                    }
                }
            }
        }), $time * 20, $time * 20);
    }

    public function removeMessage(string $id): bool {
        if (isset($this->messages[$id])) {
            unset($this->messages[$id]);
            $this->config->set("messages", $this->messages);
            $this->config->save();
            return true;
        }
        return false;
    }

    public function getMessages(): array {
        return $this->messages;
    }
}
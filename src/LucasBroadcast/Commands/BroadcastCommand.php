<?php

namespace LucasBroadcast\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use LucasBroadcast\Main;

class BroadcastCommand extends Command implements PluginOwned {

    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("broadcast", "Gérer les messages de diffusion", "§c/broadcast <add|remove|list> [time] [world] [popup:true/false] [sound] [message]", ["bc"]);
        $this->plugin = $plugin;
        $this->setPermission("lucasbroadcast.command");

    }

    public function getOwningPlugin(): Main {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!isset($args[0])) {
            $sender->sendMessage(Main::PREFIX . "§cUsage: /broadcast <add|remove|list> [time] [world] [popup:true/false] [sound] [message]");
            return false;
        }

        switch ($args[0]) {
            case "add":
                if (!isset($args[1]) || !isset($args[2]) || !isset($args[3]) || !isset($args[4]) || !isset($args[5])) {
                    $sender->sendMessage(Main::PREFIX . "§cUsage: /broadcast add <time> <world> <popup:true/false> <sound> <message>");
                    return false;
                }
                $time = (int)$args[1];
                $world = $args[2];
                $usePopup = filter_var($args[3], FILTER_VALIDATE_BOOLEAN);
                $sound = $args[4];
                $message = implode(" ", array_slice($args, 5));
                $this->plugin->addMessage($message, $time, $world, $usePopup, $sound);
                $sender->sendMessage(Main::PREFIX . "§aMessage ajouté avec succès!");
                break;

            case "remove":
                if (!isset($args[1])) {
                    $sender->sendMessage(Main::PREFIX . "§cUsage: /broadcast remove <id>");
                    return false;
                }
                $id = $args[1];
                if ($this->plugin->removeMessage($id)) {
                    $sender->sendMessage(Main::PREFIX . "§aMessage supprimé avec succès!");
                } else {
                    $sender->sendMessage(Main::PREFIX . "§cID de message invalide!");
                }
                break;

            case "list":
                $messages = $this->plugin->getMessages();
                foreach ($messages as $id => $data) {
                    $sender->sendMessage(Main::PREFIX . "§a[$id] Temps: {$data['time']}s | Monde: {$data['world']} | Popup: " . ($data['popup'] ? "Oui" : "Non") . " | Son: {$data['sound']} | Message: {$data['message']}");
                }
                break;

            default:
                $sender->sendMessage(Main::PREFIX . "§cUsage: /broadcast <add|remove|list> [time] [world] [popup:true/false] [sound] [message]");
                break;
        }
        return true;
    }
}
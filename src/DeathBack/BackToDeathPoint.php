<?php

namespace DeadBack; //Define the namespace

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;

class BackToDeathPoint extends PluginBase implements Listener{
    public $lastdeath = array();

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Plugin started successfully!"); //Message on the console to activate the plugin
    }

    public function onDisable(){
        $this->getLogger()->info("Plugin is Disabled!"); //Message on the console to disable the plugin
    }

    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        switch($command->getName()){
            case "deadback":
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Please run this command in-game."); //Message to run the command in the console
                }
                if(!isset($this->lastdeath[$sender->getName()])){
                    $sender->sendMessage(TextFormat::RED . "[DeadBack] You can only run this command after you die."); //Message when you haven't a previous position
                }else{
                    $sender->teleport($this->lastdeath[$sender->getName()][0], $this->lastdeath[$sender->getName()][1], $this->lastdeath[$sender->getName()][2]); // In order: 0 = Position object, 1 = Yaw, 2 = Pitch
                    $sender->sendMessage("[DeadBack] Teleporting to your death location.");//Message when teleport to the last location
                    unset($this->lastdeath[$sender->getName()]);
                }
                break;
        }
    }

    public function onPlayerDeath(PlayerDeathEvent $event){
        $this->lastdeath[$event->getEntity()->getName()] = [
            $event->getEntity()->getPosition(), // Direct player position
            $event->getEntity()->getYaw(), // Useful to set the right head rotation
            $event->getEntity()->getPitch() // Useful to set the right head rotation
        ];
    }
}

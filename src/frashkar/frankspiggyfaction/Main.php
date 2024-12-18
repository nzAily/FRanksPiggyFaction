<?php

/**
*  ______ _____           _     _                                     
* |  ____|  __ \         | |   | |                                    
* | |__  | |__) |__ _ ___| |__ | | ____ _ _ __ ______ _ __  _ __ ___  
* |  __| |  _  // _` / __| '_ \| |/ / _` | '__|______| '_ \| '_ ` _ \ 
* | |    | | \ \ (_| \__ \ | | |   < (_| | |         | |_) | | | | | |
* |_|    |_|  \_\__,_|___/_| |_|_|\_\__,_|_|         | .__/|_| |_| |_|
*                                                    | |              
*                                                    |_|              
* The author of this plugin is FRashkar-pm
* https://github.com/FRashkar-pm
* Discord: FireRashkar#1519
*/

declare(strict_types=1);

namespace frashkar\frankspiggyfaction;

use IvanCraft623\RankSystem\RankSystem;
use IvanCraft623\RankSystem\session\Session;
use IvanCraft623\RankSystem\tag\Tag;
use DaPigGuy\PiggyFactions\PiggyFactions;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase
{
    protected function onEnable() : void 
    {
        if (self::getRankSystem() === null || self::getPiggyFaction() === null){
            $this->getLogger()->notice("There are no RankSystem or PiggyFactions plugin.");
        } else {
            $this->setPiggyFactionSupport();
        }
    }

    private static function getRankSystem() : ?RankSystem
    {
        if (Server::getInstance()->getPluginManager()->getPlugin("RankSystem") !== null){
            return RankSystem::getInstance();
        }
        return null;
    }

    private static function getPiggyFaction() : ?PiggyFactions
    {
        if (Server::getInstance()->getPluginManager()->getPlugin("PiggyFactions") !== null){
            return PiggyFactions::getInstance();
        }
        return null;
    }

    private static function getPlayerFaction(Player $player) : string 
    {
        $piggyFactions = self::getPiggyFaction();
        if ($piggyFactions === null) return "";
        $member = $piggyFactions->getPlayerManager()->getPlayer($player);
        if ($member === null) return "";
        $faction = $member->getFaction();
        if ($faction === null) return "";
        return $faction->getName();
    }

    private static function getPlayerFactionPower(Player $player) : string
    {
        $piggyFactions = self::getPiggyFaction();
        if ($piggyFactions === null) return "";
        $member = $piggyFactions->getPlayerManager()->getPlayer($player);
        if ($member === null) return "";
        $power = round($member->getPower(), 1);
        return (string) $power;
    }

    private static function getPlayerFactionMotd(Player $player) : string
    {
        $piggyFactions = self::getPiggyFaction();
        if ($piggyFactions === null) return "";
        $member = $piggyFactions->getPlayerManager()->getPlayer($player);
        if ($member === null) return "";
        $faction = $member->getFaction();
        if ($faction === null) return "";
        return $faction->getMotd() ?? ""; // Assumes getMotd() exists
    }

    private function setPiggyFactionSupport() : void 
    {
        $rankSystem = self::getRankSystem();
        if ($rankSystem === null) return;
        $tagManager = $rankSystem->getTagManager();

        $tagManager->registerTag(new Tag("fac_name", static function(Session $user) : string {
            $player = $user->getPlayer();
            if ($player === null) return "";
            if ($player instanceof Player){
                return self::getPlayerFaction($player);
            }
            return "";
        }));

        $tagManager->registerTag(new Tag("fac_power", static function(Session $user) : string {
            $player = $user->getPlayer();
            if ($player === null) return "";
            if ($player instanceof Player){
                return self::getPlayerFactionPower($player);
            }
            return "";
        }));

        $tagManager->registerTag(new Tag("fac_motd", static function(Session $user) : string {
            $player = $user->getPlayer();
            if ($player === null) return "";
            if ($player instanceof Player){
                return self::getPlayerFactionMotd($player);
            }
            return "";
        }));
    }
}

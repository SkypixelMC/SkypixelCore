<?php

namespace Pandaa\Skypixel;

use pocketmine\plugin\PluginBase;

use pocketmine\Player;

use pocketmine\Server;

use libpmquery\PMQuery;

use libpmquery\PmQueryException;

use pocketmine\math\Vector3;

use pocketmine\entity\{Effect, EffectInstance};;

use pocketmine\level\particle\FloatingTextParticle;

use pocketmine\event\Listener;

use pocketmine\command\Command;

use pocketmine\command\CommandSender;

use pocketmine\command\ConsoleCommandSender;

use pocketmine\item\Item;

use pocketmine\event\player\PlayerExhaustEvent;

use pocketmine\event\entity\EntityDamageEvent;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\event\player\PlayerDropItemEvent;



class Core extends PluginBase implements Listener{

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    }


    public function onCommand(CommandSender $sender, command $cmd, string $label, array $args) : bool {
        switch ($cmd->getName()){
            case "servers":
                if($sender instanceof Player){
                    $player = $sender->getPlayer();
                    $this->form($player);

                } else {
                    $sender->sendMessage("You can only use this command ingame sorry.");

                }
                return true;
            break;

            case "cosmetics":
                if($sender instanceof Player){
                 $player = $sender->getPlayer();
                    $this->formCosmetics($player);
                    return true;

                }
            break;

            case "fly":
                if($sender instanceof Player){
                    $player = $sender->getPlayer();
                    $toggle = !$player->getAllowFlight();
                    $player->setAllowFlight($toggle);
                    $player->sendMessage($toggle ? "§7[§6§l!§r§7] §cFlight has been enabled" : "§7[§6§l!§r§7] §cFlight has been disabled");
                } else {
                    $sender->sendMessage("sorry but console isnt a player");
                }
                return true;
            break;

            case "text1":
                if($sender instanceof Player){
                    if($sender->hasPermission("text1.use")){
                      $x = $sender->getFloorX();
                      $y = $sender->getFloorY();
                      $z = $sender->getFloorZ();
                      $text1 = "§l§eWELCOME To\n §l§cSKY§6PIXEL";
                      $subtitle1 = "Skypixel is a developing minecraft server.\n It is based off the java server Hypixel Network.\n We have custom maps; the future is bright!";
                      $this->getLevel()->addParticle(new FloatingTextParticle(new Vector3($x, $y, $z), $text, $subtitle));
                    }
                }
                return true;
            break;

            case "text2":
                if($sender instanceof Player){
                  if($sender->hasPermission("text2.use")){
                    $x = $sender->getFloorX();
                    $y = $sender->getFloorY();
                    $z = $sender->getFloorZ();
                    $text1 = "*";
                    $subtitle1 = "*";
                    $this->getLevel()->addParticle(new FloatingTextParticle(new Vector3($x, $y, $z), $text1, $subtitle1));

                  }

                }
                return true;
            break;

            case "vanish":

                if($sender instanceof Player){
                    $sender->sendMessage("");
                } else {
                    $sender->sendMessage("Sorry but console isnt a player smh");
                }
                if($sender->hasPermission("vanish.use")){
                    $sender->sendMessage("vanish has been enabled");
                    $sender->setGamemode(3);

                }



        }


    }

    public function onJoin(PlayerJoinEvent $event){

        $player = $event->getPlayer();

        $slot1 = Item::get(388, 0, 1);

        $slot3 = Item::get(397, 0, 1);

        $slot5 = Item::get(399, 0, 1);

        $slot7 = Item::get(130, 0, 1);

        $slot9 = Item::get(378, 0, 1);

        $slot1->setCustomName("§6Shop");

        $slot3->setCustomName("§6Profile");

        $slot5->setCustomName("§6Navigator");

        $slot7->setCustomName("§6Cosmetics");

        $slot9->setCustomName("§6Gadgets");

        $player->getInventory()->clearAll();

        $player->getInventory()->setItem(0, $slot1);

        $player->getInventory()->setItem(2, $slot3);

        $player->getInventory()->setItem(4, $slot5);

        $player->getInventory()->setItem(6, $slot7);

        $player->getInventory()->setItem(8, $slot9);

        $this->formJoin($player);


        






    }

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getPlayer();
        $itemname = $player->getInventory()->getItemInHand()->getName();

        if($item->getId() == 388 || $itemname == "§6Shop"){
            $event->setCancelled();
            $this->formShop($player);
            return true;
        }

        if($item->getId() == 397 || $itemname == "§6Profile"){
            $player->sendMessage("Opening Profile..");
            $this->formProfile($player);
            return true;
        }

        if($item->getId() == 399 || $itemname == "§6Navigator"){
            $cmd = "servers";
            $this->getServer()->dispatchCommand($player, $cmd);
            return true;
        }

        if($item->getId() == 130 || $itemname == "§6Cosmetics"){
            $player->sendMessage("This is still in development..4");
            $cmd = "cosmetics";
            $this->getServer()->dispatchCommand($player, $cmd);
            return true;
        }

        if($item->getId() == 378 || $itemname == "§6Gadgets"){
            $player->sendMessage("This is still in development..5");
            return true;
        }
    }

    public function form($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (PLayer $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:
                    $this->formLobbyServers($player);

            break;

            case 1:
                  $this->formSkywarsServers($player);
            break;

            case 2:
            $this->formDuels($player);

            break;


            case 3:
            $this->formUhcChampions($player);
            break;

            case 4:
                if($player->haspermission("key4.use")){
                   $player->sendMessage("Sorry this key is still in development");
                }


            break;
            case 5:

            break;

            case 6:
            break;

            }

        });


        $form->setTitle("§l§6Server Selector");
        $form->setContent("§cWelcome To Skypixel.\nSelect a server you woud like to play on");
        $form->addButton("§l§eLobby", 0, ""); // done
        $form->addButton("§l§eSkywars", 0, "textures/items/ender_pearl"); //done
        $form->addButton("§l§eDuels", 0, "textures/items/iron_sword"); // done
        $form->addButton("§l§eUhc Champions", 0, "texutres/blocks/redstone"); // done
        $form->addButton("§l§eThe Bridge", 0, "texutres/blocks/dirt"); // still to do
        $form->addButton("§l§eBeta Games", 0, "texutres/items/unkown");
        $form->addButton("§l§eComing Soon", 0, "texutres/items/unkown");
        $form->addButton("§l§cClose", 0, "textures/blocks/barrier");
        $form->sendToPlayer($player);
        return $form;


    }




    public function formLobbyServers($player){


        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (PLayer $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:

                    $cmd = "server lobby";
                    $this->getServer()->dispatchCommand($player, $cmd);

                break;

                case 1:

                    $cmd = "server lobby2";
                    $this->getServer()->dispatchCommand($player, $cmd);

                break;

                case 2:

                    $cmd = "server lobby3";
                    $this->getServer()->dispatchCommand($player, $cmd);

                break;

                case 3:

                    $this->form($player);

                break;
            }

        });

        try{
            $query = PMQuery::query("beta.skypixelmc.tk", 25600);
            $online = (int) $query['Players'];





        



            Server::getInstance()->getLogger()->info("There are ".$online." on the queried server right now!");
        }catch(PmQueryException $e){
            //you can choose to log this if you want
            Server::getInstance()->getLogger()->info("The queried server is offline right now!");
        }

        $query = PMQuery::query("beta.skypixelmc.tk", 25600);
        $online = (int) $query['Players'];


        $form->setTitle("§l§6Server Selector");
        $form->setContent("§cThese are all the lobbies you can connect to");
        $form->addButton("§l§eLobby-01\n§a$online");
        $form->addButton("§l§eLobby-02");
        $form->addButton("§l§eLobby-03\nCOMING SOON");
        $form->addButton("§l§bBack!");
        $form->addButton("§l§cClose", 0, "textures/blocks/barrier");
        $form->sendToPlayer($player);
        return $form;


        


    }


    public function formCosmetics($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:
                    $player->sendMessage("*");

                break;

                case 1:
                    $player->sendMessage("*");
                break;

                case 2:
                    $player->sendMessage("*");
                break;
            }
        });

        $form->setTitle("COSMETIC MENU");
        $form->setContent("cosmetics.");
        $form->addButton("Capes");
        $form->addButton("Nick");
        $form->addButton("Vanish");
        $form->addButton("boots");
        $form->sendToPlayer($player);
        return $form;


    }

    public function formJoin($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            switch($result){

                case 0:
                    $player->sendMessage("§7[§l§6!§r§7] §cJoin our discord here.\n https://discord.skypixelmc.tk");

                break;

                case 1:
                    $player->sendMessage("§7[§l§6!§r§7] §cOur store is \n https://skypixel.tebex.io");

                break;

                case 2:

                break;
            }
        });

        $form->setTitle("§l§eSKYPIXEL NETWORK");
        $form->setContent("§cWelcome to the Skypixel Network minecraft server.\nClick a button below to get a link.\nfeel free to join our discord or purchase something from our store. It would help us out alot and we would appreciate it.");
        $form->addButton("§l§bDISCORD", 0, "C:\Users\jqhea\Downloads\DiscordLogoEyesWhite.png");
        $form->addButton("§l§bSTORE", 0, "textures/blocks/chest");
        $form->addButton("§l§cCLOSE", 0, "textures/blocks/barrier");
        $form->sendToPlayer($player);
        return $form;


    }

    public function formBoots($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            switch($result){


                case 0:
                    $player->sendMessage("*");

                break;

                case 1:
                    $player->sendMessage("*");

                break;

                case 2:


                break;

                case 3:

                    $player->sendMessage("in development");
                break;
            }


        });

        $form->setTitle("COSMETIC MENU");
        $form->setContent("cosmetics.");
        $from->addButton("speed");
        $form->addButton("regeneration");
        $form->addButton("slow falling");
        $form->addButton("jump boost");
        $form->sendToPlayer($player);
        return $form;
    }


    private function getGroupNameForPlayer(Player $player): string {
		$ppGroup = $this->purePerms->getUserDataMgr()->getGroup($player);
		if ($ppGroup === null) {
			// This should never happen, if it does, the server owner messed up their PurePerms config
			// We don't need to log this, PurePerms does that already
			return "";
		}
		return $ppGroup->getName();
	}

















    public function formProfile($player){
        $name = $player->getName();
        $rank = $this->purePerms->getUserDataMgr()->getGroup($player);
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            switch($result){

                case 0:

                break;

            }
        });

        $form->setTitle("PROFILE MENU");
        $form->setContent("Welcome to the Profile menu!\n\n Username: $name \n\n Rank: $rank \n\n §rKills: {kills}\n\n SkyWars Wins: {wins}\n\n Skywars Coins: {coins}\n\n Duels Wins {wins}\n\n");
        $form->addButton("OKAY!");
        $form->sendToPlayer($player);
        return $form;

    }



























    public function Hunger(PlayerExhaustEvent $exhaustEvent) {
        $exhaustEvent->setCancelled(true);
    }

















    public function damageHandler(EntityDamageEvent $event){
		$entity = $event->getEntity();
		$cause = $event->getCause();
		if($entity instanceof Player && $entity->hasPermission("nofalldamage")){
			if($cause == EntityDamageEvent::CAUSE_FALL){
				$event->setCancelled(true);
			}
		}
	}


























    public function onDrop(PlayerDropItemEvent $event){
        $event->setCancelled();

    }



































    public function formShop($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            switch($result){

                case 0:

                    $this->formStoreRanks($player);

                break;

                case 1:

                    $player->sendMessage("this will be done soon");

                break;

                case 2:

                    $this->formTitles($player);

                break;

                case 3:

                    $player->sendMessage("this will be done soon");

                break;

                case 4:

                break;
            }




        });

        $form->setTitle("SHOP MENU");
        $form->setContent("Store.");
        $form->addButton("Ranks"); //case 0:
        $form->addButton("kits"); //case 1:
        $form->addButton("Hub Titles"); //case 2:
        $form->addButton("Page2"); //case 3:
        $form->addButton("Close"); //case 4:
        $form->sendToPlayer($player);
        return $form;




    }

    public function formStoreRanks($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }


            switch($result){

                case 0;

                $this->storeRanksFlare($player);

                break;

                case 1:

                   $this->storeRanksPixel($player);

                break;

                case 2:

                    $this->storeRanksUltra($player);

                break;
            }


        });

        $form->setTitle("§6» §bSHOP MENU §6«");
        $form->setCotent("§cWelcome to the Skypixel Shop!\nHere you can look at a menu!");
        $form->addButton("§eFLARE");
        $form->addButton("§ePIXEL");
        $form->addButton("§eULTRA");
        $form->sendToPlayer($player);
        return $form;



    }


    public function storeRanksFlare($player){


        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }


            switch($result){

                case 0;

                break;
            }


        });

        $form->setTitle("FLARE RANK");
        $form->setContent("This rank has many perms such has\n\n 1.\n\n 2.\n\n 3.\n\n 4.");
        $form->addButton("Close");
        $form->sendToPlayer($player);
        return $form;


    }

    public function storeRanksPixel($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }


            switch($result){

                case 0;

                break;
            }


        });

        $form->setTitle("PIXEL RANK");
        $form->setContent("coming soon");
        $form->addButton("Close");
        $form->sendToPlayer($player);
        return $form;

    }



    public function storeRanksUltra($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }


            switch($result){

                case 0;

                break;
            }


        });

        $form->setTitle("ULTRA RANK");
        $form->setContent("coming soon");
        $form->addButton("OKAY");
        $form->sendToPlayer($player);
        return $form;
    }


    public function formTitles($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }


            switch($result){

                case 0:

                break;

                case 1:

                break;

                case 2:

                break;

                case 3:

                break;

                case 4:

                break;

                case 5:

                break;

                case 6:

                break;

                case 7:

                break;

                case 8:
                    $this->formTitlesPage($player);

                break;

                case 9:

                break;
            }


        });

        $form->setTitle("HUB TITLES");
        $form->setContent("titles for hub");
        $form->addButton("Title 1");
        $form->addButton("Title 2");
        $form->addButton("Title 3");
        $form->addButton("Title 4");
        $form->addButton("Title 5");
        $form->addButton("Title 6");
        $form->addButton("Title 7");
        $form->addButton("Title 8");
        $form->addButton("PAGE 2");
        $form->addButton("OKAY!");
        $form->sendToPlayer($player);
        return $form;
    }



    public function formTitlesPage($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }


            switch($result){

                case 0:

                break;

                case 1:

                break;

                case 2:

                break;

                case 3:

                break;

                case 4:

                break;

                case 5:

                break;

                case 6:

                break;

                case 7:

                break;

                case 8:

                break;

            }


        });

        $form->setTitle("HUB TITLES");
        $form->setContent("titles for hub");
        $form->addButton("Title 1");
        $form->addButton("Title 2");
        $form->addButton("Title 3");
        $form->addButton("Title 4");
        $form->addButton("Title 5");
        $form->addButton("Title 6");
        $form->addButton("Title 7");
        $form->addButton("Title 8");
        $form->addButton("Close");
        $form->sendToPlayer($player);
        return $form;
    }


    public function formSkywarsServers($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (PLayer $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:

                    $cmd = "server skywars";

                    $this->getServer()->dispatchCommand($player, $cmd);
                break;

                case 1:

                    $cmd = "server skywars2";
                    $this->getServer()->dispatchCommand($player, $cmd);

                break;

                case 2:

                    $this->form($player);

                break;

            }

        });

        $form->setTitle("§l§6Server Selector");
        $form->setContent("All the skywars servers we have that you can play on!");
        $form->addButton("§l§eSkywars-01");
        $form->addButton("§l§eSkywars-02");
        $form->addButton("§l§bBack!");
        $form->addButton("§l§cClose");
        $form->sendToPlayer($player);
        return $form;




    }





    public function formDuels($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (PLayer $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:

                    $cmd = "server duels";

                    $tis->getServer()->dispatchCommand("$player, $cmd");

                break;
            }

        });

        $form->setTitle("Server Selector");
        $form->setContent("All the Duels servers you can play on!");
        $form->addButton("Duels-01");
        $form->addButton("Duels-02");
        $form->addButton("Back!");
        $form->addButton("Close");
        $form->sendToPlayer($player);
        return $form;

    }



    public function formUhcChampions($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (PLayer $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:

                    $player->sendMessage("teleporting you to Hub-01");

                break;
            }

        });

        $form->setTitle("COMING SOON");
        $form->setConent("coming soon");
        $form->addButton("Lobby-01");
        $form->addButton("Lobby-02");
        $form->addButton("back!");
        $form->addButton("Close");
        $form->sendToPlayer($player);
        return $form;

    }

    public function formComingSoon($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (PLayer $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){
                case 0:

                    $player->sendMessage("teleporting you to Hub-01");

                    break;
            }

        });

        $form->setTitle("Server Selector");
        $form->setContent("All the the Skypixel Hcf servers");
        $form->addButton("HCF");
        $form->addButton("Close");
        $form->sendToPlayer($player);
        return $form;
    }







}

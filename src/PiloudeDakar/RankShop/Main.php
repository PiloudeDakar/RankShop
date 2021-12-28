<?php

declare(strict_types=1);

namespace PiloudeDakar\RankShop;

use PiloudeDakar\RankShop\libs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use onebone\economyapi\EconomyAPI;
use _64FF00\PurePerms\PurePerms;
use _64FF00\PurePerms\PPGroup;

class Main extends PluginBase{

    private array $ranks = [];

    protected function onEnable(): void
    {
        @mkdir($this->getDataFolder());
        if (!file_exists($this->getDataFolder() . 'config.yml')){
            file_put_contents($this->getDataFolder() . 'config.yml', '---
#title of the main form
title: RankShop by PiloudeDakar

#content of the main form
content: Choose a rank to buy

#exampleRank:
#  title: Example rank
#  content: Buy the example rank
#  name: [the perfect name of the rank, with colors] §3ExampleRank
#  price: 100000
...');
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command == 'up'){
            if ($sender instanceof Player){
                $this->openRanksShop($sender);
                return true;
            }
        }
        return true;
    }

    public function openRanksShop(Player $sender){
        $config = $this->getConfig();
        $ranks = $config->getAll();
        $form = new SimpleForm(function (Player $player, int $data = null) use ($sender) {
            if ($data !== null){
                $rank = $this->ranks[$data];
                $this->openOneRankShop($sender, $rank);
            }
        });
        $form->setTitle($config->get('title'));
        $form->setContent($config->get('content'));
        foreach ($ranks as $key => $rank){
            if ($key !== 'title' and $key !== 'content'){
                $form->addButton($config->getNested($key . '.name') . '§r - ' . $config->getNested($key . '.price') . '$');
                array_push($this->ranks, $key);
            } else {
                unset($ranks[$key]);
            }
        }
        $sender->sendForm($form);
    }

    public function openOneRankShop(Player $sender, string $rank)
    {
        $config = $this->getConfig();
        $form = new SimpleForm(function (Player $player, $data) use ($sender, $config, $rank){
            if ($data !== null){
                $return = EconomyAPI::getInstance()->reduceMoney($sender, $config->getNested($rank . '.price'));
                if ($return == 1){
                    $PPgroup = $this->getServer()->getPluginManager()->getPlugin('PurePerms')->getGroup($config->getNested($rank . '.name'));
                    if ($PPgroup instanceof PPGroup){
                        $this->getServer()->getPluginManager()->getPlugin('PurePerms')->setGroup($sender, $PPgroup);
                        $sender->sendMessage('You have bought the rank : ' . $config->getNested($rank . '.name'));
                    }
                } else {
                    $sender->sendMessage('Not enhough money');
                }
            }
            #setGroup(IPlayer $player, PPGroup $group, $WorldName = null, $time = -1)
            #reduceMoney($player, float $amount, ?Currency $currency = null, ?Issuer $issuer = null, bool $force = false)
        });
        $form->setTitle($config->getNested($rank . '.title'));
        $form->setContent($config->getNested($rank . '.content'));
        $form->addButton('§aBuy - §2' . $config->getNested($rank . '.price') . '$');
        $sender->sendForm($form);
    }
}
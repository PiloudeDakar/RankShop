<?php

declare(strict_types=1);

namespace PilourdeDakar\RankShop;

use PilourdeDakar\RankShop\libs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use onebone\economyapi\EconomyAPI;
use _64FF00\PurePerms\PurePerms;

class Main extends PluginBase{

    protected function onEnable(): void
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command == 'up'){
            if ($sender instanceof Player)
            $this->openRankShop($sender);
            return true;
        }
        return true;
    }

    public function openRankShop(Player $sender){
        $config = $this->getConfig();
        $ranks = $config->getAll();
        $form = new SimpleForm(function (Player $player, int $data = null) use ($sender, $ranks) {
            if ($data !== null){
                $rank = $ranks[$data];
                $this->openOneRankShop($sender, $rank);
            }
        });
        $form->setTitle($config->get('title'));
        $form->setContent($config->get('content'));
        foreach ($ranks as $rank){
            if ($rank !== 'title' and $rank !== 'content'){
                $form->addButton($config->getNested($rank . '.name') . '§r - ' . $config->getNested($rank . '.price') . '$');
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
                    $group = PurePerms::getInstance()->getGroup($config->getNested($rank . '.name'));
                    PurePerms::getInstance()->setGroup($sender, $group);
                }
            }
            #setGroup(IPlayer $player, PPGroup $group, $WorldName = null, $time = -1)
            #reduceMoney($player, float $amount, ?Currency $currency = null, ?Issuer $issuer = null, bool $force = false)
        });
        $form->setTitle($config->getNested($rank . '.title'));
        $form->setContent($config->getNested($rank . '.content'));
        $form->addButton('§aBuy - §2' . $config->getNested($rank . '.price') . '$');
    }
}
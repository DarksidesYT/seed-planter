<?php

namespace darksidesyt\SeedPlanter\Events\Listener;

use darksidesyt\SeedPlanter\Utils\Utils;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\Player;

class PlayerListener implements Listener {

    public function PlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();
        if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            if (isset(Utils::getIntoConfig("items")[$item->getId()])) {
                if ($block->getId() === Block::FARMLAND) {
                    $radius = Utils::getIntoConfig("items")[$item->getId()];
                    $seeds = self::getSeeds($player);
                    $minX = $block->getX() - $radius;
                    $maxX = $block->getX() + $radius;
                    $minZ = $block->getZ() - $radius;
                    $maxZ = $block->getZ() + $radius;
                    $y = $block->y;
                    for ($x = $minX; $x <= $maxX; $x++) {
                        for ($z = $minZ; $z <= $maxZ; $z++) {
                            $block = $player->getLevel()->getBlockAt($x, $y, $z);
                            if ($block->getId() === Block::FARMLAND) {
                                if ($player->getLevel()->getBlockIdAt($x, $y+1, $z) === 0) {
                                    if ($seeds[ItemIds::SEEDS] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::WHEAT_BLOCK));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::SEEDS));
                                        $seeds[ItemIds::SEEDS]--;
                                    } else if ($seeds[ItemIds::POTATO] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::POTATO_BLOCK));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::POTATO));
                                        $seeds[ItemIds::POTATO]--;
                                    } else if ($seeds[ItemIds::CARROT] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::CARROT_BLOCK));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::CARROT));
                                        $seeds[ItemIds::CARROT]--;
                                    } else if ($seeds[ItemIds::PUMPKIN_SEEDS] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::PUMPKIN_STEM));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::PUMPKIN_SEEDS));
                                        $seeds[ItemIds::PUMPKIN_SEEDS]--;
                                    } else if ($seeds[ItemIds::MELON_SEEDS] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::MELON_STEM));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::MELON_SEEDS));
                                        $seeds[ItemIds::MELON_SEEDS]--;
                                    } else if ($seeds[ItemIds::BEETROOT_SEEDS] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::BEETROOT_BLOCK));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::BEETROOT_SEEDS));
                                        $seeds[ItemIds::BEETROOT_SEEDS]--;
                                    } else if ($seeds[ItemIds::NETHER_WART] > 0) {
                                        $player->getLevel()->setBlock(new Vector3($x, $y+1, $z), Block::get(Block::NETHER_WART_PLANT));
                                        $player->getInventory()->removeItem(Item::get(ItemIds::NETHER_WART));
                                        $seeds[ItemIds::NETHER_WART]--;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Player $player
     * @return int[]
     */
    public static function getSeeds(Player $player): array {
        $inventory = $player->getInventory()->getContents();
        $ids = [ItemIds::SEEDS, ItemIds::POTATO, ItemIds::CARROT, ItemIds::PUMPKIN_SEEDS, ItemIds::MELON_SEEDS, ItemIds::BEETROOT_SEEDS, ItemIds::NETHER_WART];
        $seeds = [ItemIds::SEEDS => 0, ItemIds::POTATO => 0, ItemIds::CARROT => 0, ItemIds::PUMPKIN_SEEDS => 0, ItemIds::MELON_SEEDS => 0, ItemIds::BEETROOT_SEEDS => 0, ItemIds::NETHER_WART => 0];
        foreach ($inventory as $item) {
            if (in_array($item->getId(), $ids)) {
                $seeds[$item->getId()] += $item->getCount();
            }
        }
        return $seeds;
    }
}

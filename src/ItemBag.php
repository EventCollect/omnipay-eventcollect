<?php

namespace Omnipay\EventCollect;

use Omnipay\Common\ItemInterface;

class ItemBag extends \Omnipay\Common\ItemBag
{
    public function add($item): void
    {
        if ($item instanceof ItemInterface) {
            $this->items[] = $item;
        } else {
            $this->items[] = new Item($item);
        }
    }
}

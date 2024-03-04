<?php

namespace Omnipay\EventCollect;

class Item extends \Omnipay\Common\Item
{
    public function getPriceInteger(): int
    {
        $price = $this->getPrice();

        return $price * 100;
    }
}

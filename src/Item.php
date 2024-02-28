<?php

namespace Omnipay\EventCollect;

class Item extends \Omnipay\Common\Item
{
    public function getPriceInteger(): int
    {
        $price = $this->getPrice();

        if (is_int($price)) {
            return $price;
        }

        return $price * 100;
    }
}

<?php

namespace App\Model;

readonly class Order
{
    public int $id;

    /** @var Product[] */
    public array $products;
}
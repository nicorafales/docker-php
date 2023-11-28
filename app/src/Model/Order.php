<?php

namespace App\Model;

use DateTimeImmutable;
use DateTimeInterface;

readonly class Order
{
    public DateTimeInterface $orderDate;

    public function __construct(
        public int $id,
        string $order_date,        
    ) {
        $this->orderDate = new DateTimeImmutable($order_date);
    }
}
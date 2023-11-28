<?php

namespace App\Model;

readonly class Product
{
    public function __construct(
        public string $name,
        public int $quantity,

    ) {        
    }
}
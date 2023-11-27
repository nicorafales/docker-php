<?php

namespace App\Repository;

class ProductsRepository
{
    public function getAll(): array
    {
        return [
            'Potato',
            'Carrot',
            'Onion',
        ];
    }
}
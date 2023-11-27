<?php

namespace App\Factory;

use App\Database\MariaDbConnection;
use App\Repository\ProductsRepository;
use App\Repository\OrderWithProductsRepository;

/**
 * @todo this could use more abstraction.
 */
class RepositoryFactory
{
    public function __construct(
        private array $instances = []
    ) {        
    }

    public function productsRepo(): ProductsRepository
    {
        return $this->instances[__FUNCTION__] ??= new ProductsRepository(
        );
    }

    public function orderWithProductsRepo(): OrderWithProductsRepository
    {        
        return $this->instances[__FUNCTION__] ??= new OrderWithProductsRepository(
            connection: MariaDbConnection::make()->connection()
        );
    }
}
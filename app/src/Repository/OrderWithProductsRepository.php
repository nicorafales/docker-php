<?php

namespace App\Repository;

use App\Model\Order;
use App\Model\Product;
use PDO;

class OrderWithProductsRepository
{
    public function __construct(
        private PDO $connection,
    ) {
    }

    public function getOrderWithProducts(int $orderId): array
    {
        $qry = <<<SQL
            SELECT 
                `orders`.*, 
            FROM `orders`
            INNER JOIN `order_item` 
                ON `order_item`.`order_id` = `orders`.`id`
            WHERE `orders`.`id` = :orderId
        SQL;

        $stmt = $this->connection->prepare($qry);

        $stmt->execute([
            'orderId' => $orderId,
        ]);

        dd('asd', $stmt->fetchAll(PDO::FETCH_ASSOC));

        return $stmt->fetchAll(
            \PDO::FETCH_CLASS,
            Order::class,
        );
    }

    public function store(string $productName, int $quantity): int
    {
        $newProductStmt = '';
        $order ??= $this->createOrder();
        $orderId = $order->id;

        //todo: later on, I want to support multiple products per order.
        $product = new Product;
        $product->name = $productName;
        $product->quantity = $quantity;
        $products = [$product];

        foreach ($products as $idx => $product) {
            $newProductStmt .= <<<SQL
                (
                    {$orderId},
                    :{$idx}_name,
                    :{$idx}_quantity
                ),
            SQL;

            // we have to do this to avoid SQL injection.
            $bindings["{$idx}_name"] = $product->name;
            $bindings["{$idx}_quantity"] = $product->quantity;
        }

        $qry = <<<SQL
            INSERT INTO `order_item` (
                `order_id`,
                `name`,
                `quantity`,
            ) VALUES 
                $newProductStmt
            ;
        SQL;

        $stmt = $this->connection->prepare($qry);

        $stmt->execute($bindings);

        return $orderId;
    }

    private function createOrder(): Order
    {
        $fetchStmt = $this->connection->prepare(<<<SQL
            SELECT * FROM `orders` ORDER BY 1 DESC LIMIT 1;
        SQL);

        $res = $fetchStmt->fetchAll(PDO::FETCH_CLASS, Order::class);

        // dd('dasxcz',$res);

        return current(
            $fetchStmt->fetchAll(PDO::FETCH_CLASS, Order::class)
        );
    }
}
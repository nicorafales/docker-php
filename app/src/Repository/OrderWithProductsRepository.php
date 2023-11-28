<?php

namespace App\Repository;

use App\Model\Order;
use App\Model\Product;
use PDO;
use PDOException;

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
        //todo: this could be added to a pre-existing order. I might need some UX refactor too.
        $order ??= $this->createOrder();
        $orderId = $order->id;

        //todo: later on, I want to support multiple products per order.
        $products = [new Product($productName, $quantity)];

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

        // remove trailing comma.
        $newProductStmt = rtrim($newProductStmt, ',');

        $qry = <<<SQL
            INSERT INTO `order_items` (
                `order_id`,
                `product_name`,
                `quantity`
            ) VALUES 
                $newProductStmt
            ;
        SQL;

        $stmt = $this->connection->prepare($qry);

        try {
            $stmt->execute($bindings);

        } catch (PDOException $e) {
            throw new \Exception("Couldn't create an order for ($productName, $quantity)", previous: $e);
        }

        return $orderId;
    }

    private function createOrder(): Order
    {
        $this->connection->exec(<<<SQL
            INSERT INTO orders (order_date) VALUES (NOW());
        SQL);

        $fetchStmt = $this->connection->query(<<<SQL
            SELECT * FROM `orders` ORDER BY 1 DESC LIMIT 1;
        SQL);

        /** @var Order $order */
        $order = current($fetchStmt->fetchAll(
            PDO::FETCH_FUNC, 
            static fn (int $id, string $order_date): Order => new Order($id, $order_date)
        ));

        return $order;
    }
}

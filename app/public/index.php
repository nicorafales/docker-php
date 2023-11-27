<!DOCTYPE html>
<?php

use App\Factory\RepositoryFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';


if (!function_exists('dump')) {
    function dump(...$args): void {
        echo '<pre>'. print_r($args, true) . '<pre/>';
    }
}

if (!function_exists('dd')) {
    function dd(...$args): never {
        die(dump(...$args));    
    }
}

$factory = new RepositoryFactory();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product'];
    $quantity = $_POST['quantity'];
    
    $orderWithProductsRepo = $factory->orderWithProductsRepo();
    $orderNumber = $orderWithProductsRepo->store($productName, $quantity);
    
} else {
    $products = $factory->productsRepo()->getAll();
}

?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShoPHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>

    </style>
</head>

<body>
    <div 
        class="bg-grey text-secondary px-4 py-5 text-center" 
        style="height: 100vh;"
    >
    <div class="py-5">
        <h1 class="display-5 fw-bold text-white">Shop orders</h1>
        <div class="col-log-6 mx-auto">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="fs-5 mb-4">
                Order #<?php echo $orderNumber; ?> completed.
            </p>
            <a href="/">Make new order</a>


        <?php else: ?>
            <p class="fs-5 mb-4">Choose your products</p>

            <form method="POST">
                <div class="row g-3">
                    <div class="col">
                        <select 
                            name="product" 
                            class="form-select" 
                            aria-label="Default select example"
                        >
                            <option selected>Select a product</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product; ?>">
                                    <?php echo $product; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col">
                        <input name="quantity" type="number" class="form-control" placeholder="Quantity" aria-label="Quantity">
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-info" type="submit">
                            Buy
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>

        </div>
    </div>

    </div>
</body>

<?php
declare(strict_types=1);

namespace App\Command;

use Dotenv\Dotenv;

final class LoadEnvVars
{
    public static function handle()
    {
        // require 'vendor/autoload.php';

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();
    }
}
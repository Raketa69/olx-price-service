<?php

declare(strict_types=1);

use App\Helpers\Helper;
use App\Http\Controllers\SubscriptionController;
use App\Services\SubscriptionService;

require __DIR__ . '/../autoload.php';

$service = new SubscriptionService();
$controller = new SubscriptionController($service);

$requestUri = $_SERVER['REQUEST_URI'];

try {
    switch ($requestUri) {
        case '/subscribe':
            $controller->subscribe(Helper::getPost());
            break;
        case '/unsubscribe':
            $controller->unsubscribe(Helper::getPost());
            break;
        default:
            http_response_code(404);
            echo "404 Not Found";
            break;
    }


} catch (Exception $e) {

    http_response_code(404);

    echo $controller->responseJson("Not found.");
}
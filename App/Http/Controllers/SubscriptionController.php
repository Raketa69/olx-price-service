<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Db;
use App\Models\Subscription;
use App\Services\SubscriptionService;

class SubscriptionController
{
    protected array $validData;

    public function __construct(
        protected SubscriptionService $service,
    ) {
    }

    public function subscribe($request): void
    {
        if ($this->service->subscribe($request)) {
            $this->responseJson(["message" => "Subscription successful"]);
        }

        $this->responseJson(["message" => "Error"], 400);
    }

    public function unsubscribe($request): void
    {
        if ($this->service->unsubscribe($request)) {
            $this->responseJson(["message" => "Subscription cancelled successful"]);
        }

        $this->responseJson(["message" => "Error"], 400);
    }

    public function responseJson(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
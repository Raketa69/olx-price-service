<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Subscription;

class SubscriptionService
{
    private HttpService $httpService;
    public function __construct()
    {
        $this->httpService = new HttpService();
    }

    public function subscribe(array $data): bool
    {
        $newSubs = new Subscription();
        $newSubs->link = $data['link'];
        $newSubs->email = $data['email'];

        if (Subscription::isExist($newSubs)) {
            return false;
        }

        if ($info = $this->httpService->getDataByUrl($data['link'])) {
            $newSubs->currency = $info->data->params[0]->value->currency;
            $newSubs->price = $info->data->params[0]->value->value;
            $newSubs->offer_id = (string)$info->data->id;

            $newSubs->insert();
            return true;
        }

        return false;
    }

    public function unsubscribe(array $data): bool
    {
        $sub = Subscription::find($data);

        if ($sub === null) {
            return false;
        }

        $sub->delete();
        return true;
    }

    
}
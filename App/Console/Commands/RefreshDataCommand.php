<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\HttpService;
use App\Services\MailService;

class RefreshDataCommand
{
    private HttpService $httpService;
    private MailService $mailService;

    public function __construct()
    {
        $this->httpService = new HttpService();
        $this->mailService = new MailService();
    }
    public function handle(): void
    {
        $subs = Subscription::findAll();

        $uniqueSubs = $this->getUniqueByParameter($subs, 'offer_id');

        $newPriceSubs = [];
        foreach ($uniqueSubs as $sub) {
            $newData = $this->httpService->getDataByUrl($sub->link);
            if ($newData->data->params[0]->value->value != $sub->price) {
                $sub->new_price = $newData->data->params[0]->value->value;
                $sub->update();
                $newPriceSubs[] = $sub;
            }
        }

        $filterSubs = [];
        foreach ($newPriceSubs as $sub) {
            $filterSubs[] = array_filter($subs, function ($value) use ($sub) {
                return $sub->offer_id == $value->offer_id;
            });
        }

        $filterSubs = $this->flattenArray($filterSubs);

        foreach ($filterSubs as $sub) {
            $this->mailService->sendEmail($sub);
        }
    }

    private function getUniqueByParameter($objects, $parameter)
    {
        $values = array_map(function ($obj) use ($parameter) {
            return $obj->$parameter;
        }, $objects);

        $uniqueValues = array_unique($values);

        $uniqueObjects = array_filter($objects, function ($obj) use (&$uniqueValues, $parameter) {
            if (in_array($obj->$parameter, $uniqueValues)) {
                unset($uniqueValues[array_search($obj->$parameter, $uniqueValues)]);
                return true;
            }
            return false;
        });

        return $uniqueObjects;
    }

    private function flattenArray(array $array): array
    {
        $result = [];

        foreach ($array as $element) {
            if (is_array($element)) {
                $result = array_merge($result, $this->flattenArray($element));
            } else {
                $result[] = $element;
            }
        }

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

final class Subscription extends Model
{
    public const TABLE = 'subscriptions';
    public string $link;
    public string $email;
    public int $price;
    public string $currency;
    public string $offer_id;
    public ?int $new_price;

    public function getModelName()
    {
        return self::class;
    }

    public static function isExist(Subscription $newSubs): bool
    {
        $subs = static::findAll();

        foreach ($subs as $sub) {
            if (
                $sub->link === $newSubs->link &&
                $sub->email === $newSubs->email
            ) {
                return true;
            }
        }

        return false;
    }

    public static function find(array $data): Subscription|null
    {
        $subs = static::findAll();

        foreach ($subs as $sub) {
            if (
                $sub->link === $data['link'] &&
                $sub->email === $data['email']
            ) {
                return $sub;
            }
        }

        return null;
    }
}

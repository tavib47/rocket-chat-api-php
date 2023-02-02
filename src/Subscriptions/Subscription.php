<?php

namespace ATDev\RocketChat\Subscriptions;

use ATDev\RocketChat\Common\Request;
use ATDev\RocketChat\Ims\Counters;

/**
 * Im class
 */
class Subscription extends Request
{
    use \ATDev\RocketChat\Subscriptions\Data;

    /**
     * Get all subscriptions.
     *
     * @param int $offset
     * @param int $count
     * @return \ATDev\RocketChat\Subscriptions\Collection|bool
     */
    public static function all($updatedSince = null)
    {
        static::send(
            'subscriptions.get',
            'GET',
            [
                'updatedSince' => $updatedSince,
            ]
        );

        if (!static::getSuccess()) {
            return false;
        }

        $subscriptions = new \ATDev\RocketChat\Subscriptions\Collection();
        $response = static::getResponse();

        if (isset($response->update)) {
            foreach ($response->update as $sub) {
              $subscriptions->add(Subscription::createOutOfResponse($sub));
            }
        }

        return $subscriptions;
    }

    public function get() {
      static::send(
        "subscriptions.getOne",
        "GET",
        ["roomId" => $this->getRoomId()]
      );

      if (!static::getSuccess()) {
        return false;
      }

      return $this->updateOutOfResponse(static::getResponse());
    }

    public function read() {
      static::send(
        "subscriptions.read",
        "POST",
        ["roomId" => $this->getRoomId()]
      );

      if (!static::getSuccess()) {
        return false;
      }

      return true;
    }

    public function unRead() {
      static::send(
        "subscriptions.unread",
        "POST",
        ["roomId" => $this->getRoomId()]
      );

      if (!static::getSuccess()) {
        return false;
      }

      return true;
    }
}

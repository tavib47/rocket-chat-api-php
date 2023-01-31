<?php

namespace ATDev\RocketChat\Subscriptions;

/**
 * Im collection class
 */
class Collection extends \ATDev\RocketChat\Common\Collection
{

    /**
     * @param \ATDev\RocketChat\Subscriptions\Subscription $element
     * @return bool|true
     */
    public function add($element)
    {
        if (!($element instanceof Subscription)) {
            return false;
        }

        return parent::add($element);
    }
}

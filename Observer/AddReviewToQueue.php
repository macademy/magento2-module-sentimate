<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddReviewToQueue implements ObserverInterface
{
    /**
     * Adds a review to the queue.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(
        Observer $observer,
    ): void {
        $review = $observer->getEvent()->getData('object');

        if ($review->isObjectNew()) {
            // Add logic to push message to the queue.
        }
    }
}

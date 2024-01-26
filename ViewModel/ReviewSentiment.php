<?php

declare(strict_types=1);

namespace Macademy\Sentimate\ViewModel;

use Macademy\Sentimate\Model\ReviewSentimentService;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ReviewSentiment implements ArgumentInterface
{
    /**
     * Constructor for review sentiment.
     *
     * @param ReviewSentimentService $reviewSentimentService
     */
    public function __construct(
        private readonly ReviewSentimentService $reviewSentimentService,
    ) {
    }

    /**
     * Get the review sentiment data by review id.
     *
     * @param int $reviewId
     * @param string|null $key
     * @return string|null
     */
    public function getDataByReviewId(
        int $reviewId,
        ?string $key,
    ): ?string {
        $result = null;

        try {
            $reviewSentiment = $this->reviewSentimentService->getByReviewId(($reviewId));
            $result = $reviewSentiment->getId()
                ? ucfirst($reviewSentiment->getData($key))
                : null;
        } catch (NoSuchEntityException $exception) {
            // Do not do anything
        }

        return $result;
    }
}

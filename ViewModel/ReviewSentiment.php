<?php

declare(strict_types=1);

namespace Macademy\Sentimate\ViewModel;

use Macademy\Sentimate\Model\ResourceModel\ReviewSentiment as ReviewSentimentResourceModel;
use Macademy\Sentimate\Model\ReviewSentimentFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ReviewSentiment implements ArgumentInterface
{
    /**
     * Constructor for review sentiment.
     *
     * @param ReviewSentimentResourceModel $reviewSentimentResourceModel
     * @param ReviewSentimentFactory $reviewSentimentFactory
     */
    public function __construct(
        private readonly ReviewSentimentResourceModel $reviewSentimentResourceModel,
        private readonly ReviewSentimentFactory $reviewSentimentFactory,
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
        $reviewSentiment = $this->reviewSentimentFactory->create();
        $this->reviewSentimentResourceModel->load($reviewSentiment, $reviewId, 'review_id');

        return $reviewSentiment->getId()
            ? ucfirst($reviewSentiment->getData($key))
            : null;
    }
}

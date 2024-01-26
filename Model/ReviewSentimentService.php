<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Model;

use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ReviewSentimentService
{
    /**
     * Review sentiment service constructor.
     *
     * @param ResourceModel\ReviewSentiment $reviewSentimentResourceModel
     * @param ReviewSentimentFactory $reviewSentimentFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ResourceModel\ReviewSentiment $reviewSentimentResourceModel,
        private readonly ReviewSentimentFactory $reviewSentimentFactory,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Save the sentiment analysis results.
     *
     * @param ReviewSentiment $reviewSentiment
     * @return void
     */
    public function save(
        ReviewSentiment $reviewSentiment,
    ): void {
        try {
            $this->reviewSentimentResourceModel->save($reviewSentiment);
        } catch (Exception $e) {
            $this->logger->error(__('Failed to save sentiment analysis: %1', $e->getMessage()));
        }
    }

    /**
     * Get sentiment analysis by Review ID.
     *
     * @param int $reviewId
     * @return ReviewSentiment
     * @throws NoSuchEntityException
     * @throws NoSuchEntityException
     */
    public function getByReviewId(
        int $reviewId,
    ): ReviewSentiment {
        $reviewSentiment = $this->reviewSentimentFactory->create();
        $this->reviewSentimentResourceModel->load($reviewSentiment, $reviewId, 'review_id');

        if (!$reviewSentiment->getId()) {
            throw new NoSuchEntityException(
                __('The review sentiment with %1 review ID does not exist.', $reviewId)
            );
        }

        return $reviewSentiment;
    }
}

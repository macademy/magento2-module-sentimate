<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Model;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class ReviewConsumer
{
    /**
     * Constructor for queue consumer.
     *
     * @param GuzzleClient $guzzleClient
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     * @param ResourceModel\ReviewSentiment $reviewSentimentResourceModel
     * @param ReviewSentimentFactory $reviewSentimentFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        private readonly GuzzleClient $guzzleClient,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
        private readonly ResourceModel\ReviewSentiment $reviewSentimentResourceModel,
        private readonly ReviewSentimentFactory $reviewSentimentFactory,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly EncryptorInterface $encryptor,
    ) {
    }

    /**
     * Queue consumer process handler.
     *
     * @param string $message
     * @return void
     */
    public function process(
        string $message,
    ): void {
        try {
            $deserializedMessage = $this->serializer->unserialize($message);
            $title = $deserializedMessage['title'];
            $detail = $deserializedMessage['detail'];
            $text = "$title: $detail";
            $apiKey = $this->scopeConfig->getValue('macademy_sentimate/rapidapi/api_key');
            $decryptedApiKey = $this->encryptor->decrypt($apiKey);

            $response = $this->guzzleClient->request(
                'POST',
                'https://twinword-sentiment-analysis.p.rapidapi.com/analyze/',
                [
                    'form_params' => [
                        'text' => $text,
                    ],
                    'headers' => [
                        'X-RapidAPI-Host' => 'twinword-sentiment-analysis.p.rapidapi.com',
                        'X-RapidAPI-Key' => $decryptedApiKey,
                        'content-type' => 'application/x-www-form-urlencoded',
                    ],
                ],
            );

            $body = $response->getBody();
            $deserializedResponse = $this->serializer->unserialize($body);

            $this->logger->info('Sentiment Analysis', [
                'Message' => $deserializedMessage,
                'Response Body' => $deserializedResponse,
            ]);

            if (is_array($deserializedResponse)
                && isset($deserializedResponse['type'], $deserializedResponse['score'], $deserializedResponse['ratio'])
            ) {
                $reviewSentiment = $this->reviewSentimentFactory->create();
                $reviewSentiment->setData([
                    'review_id' => $deserializedMessage['review_id'],
                    'type' => $deserializedResponse['type'],
                    'score' => $deserializedResponse['score'],
                    'ratio' => $deserializedResponse['ratio'],
                ]);

                try {
                    $this->reviewSentimentResourceModel->save($reviewSentiment);
                } catch (Exception $e) {
                    $this->logger->error(__('Failed to save sentiment analysis: %1', $e->getMessage()));
                }
            } else {
                $stringResponse = implode(', ', $deserializedResponse);
                $this->logger->error(__('Sentiment Analysis API did not return expected results: %1', $stringResponse));
            }
        } catch (GuzzleException $exception) {
            $this->logger->error(__('Sentiment Analysis API returned an error: %1', $exception->getMessage()));
        } catch (Exception $exception) {
            $this->logger->error(__('Failed to deserialize sentiment analysis results: %1', $exception->getMessage()));
        }
    }
}

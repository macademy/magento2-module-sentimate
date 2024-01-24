<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Model;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
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
     */
    public function __construct(
        private readonly GuzzleClient $guzzleClient,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer,
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

            $response = $this->guzzleClient->request(
                'POST',
                'https://twinword-sentiment-analysis.p.rapidapi.com/analyze/',
                [
                    'form_params' => [
                        'text' => $text,
                    ],
                    'headers' => [
                        'X-RapidAPI-Host' => 'twinword-sentiment-analysis.p.rapidapi.com',
                        'X-RapidAPI-Key' => 'API_KEY_GOES_HERE',
                        'content-type' => 'application/x-www-form-urlencoded',
                    ],
                ],
            );

            $this->logger->info('Sentiment Analysis', [
                'Message' => $deserializedMessage,
                'Response Body' => $response->getBody(),
            ]);
        } catch (GuzzleException $exception) {
            $this->logger->error(__('Sentiment Analysis API returned an error: %1', $exception->getMessage()));
        } catch (Exception $exception) {
            $this->logger->error(__('Failed to deserialize sentiment analysis results: %1', $exception->getMessage()));
        }
    }
}

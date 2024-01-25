<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Model;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class ReviewConsumer
{
    /**
     * Constructor for queue consumer.
     *
     * @param GuzzleClient $guzzleClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly GuzzleClient $guzzleClient,
        private readonly LoggerInterface $logger,
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
            $response = $this->guzzleClient->request(
                'POST',
                'https://twinword-sentiment-analysis.p.rapidapi.com/analyze/',
                [
                    'form_params' => [
                        'text' => 'great value in its price range!'
                    ],
                    'headers' => [
                        'X-RapidAPI-Host' => 'twinword-sentiment-analysis.p.rapidapi.com',
                        'X-RapidAPI-Key' => 'API_KEY_GOES_HERE',
                        'content-type' => 'application/x-www-form-urlencoded',
                    ],
                ],
            );

            $this->logger->info('Sentiment Analysis', [
                'Message' => $message,
                'Response Body' => $response->getBody(),
            ]);
        } catch (GuzzleException $exception) {
            $this->logger->error(__('Sentiment Analysis API returned an error: %1', $exception->getMessage()));
        }
    }
}

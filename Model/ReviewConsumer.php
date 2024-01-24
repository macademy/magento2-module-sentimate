<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Model;

class ReviewConsumer
{
    /**
     * Queue consumer process handler.
     *
     * @param string $message
     * @return void
     */
    public function process(
        string $message,
    ): void {
        // Code to execute on message
    }
}

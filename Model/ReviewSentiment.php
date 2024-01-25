<?php

declare(strict_types=1);

namespace Macademy\Sentimate\Model;

use Magento\Framework\Model\AbstractModel;

class ReviewSentiment extends AbstractModel
{
    /**
     * Primary id.
     *
     * @var string $_idFieldName
     */
    protected $_idFieldName = ResourceModel\ReviewSentiment::ID_FIELD_NAME;

    /**
     * Initialize with resource model.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\ReviewSentiment::class);
    }
}

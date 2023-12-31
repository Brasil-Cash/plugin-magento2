<?php

namespace Brasilcash\Gateway\Model\Adminhtml\Source;
use Magento\Payment\Model\MethodInterface;

class PaymentAction implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => MethodInterface::ACTION_AUTHORIZE,
                'label' => __('Authorize')
            ],
            [
                'value' => MethodInterface::ACTION_AUTHORIZE_CAPTURE,
                'label' => __('Authorize and Capture')
            ]
        ];
    }
}
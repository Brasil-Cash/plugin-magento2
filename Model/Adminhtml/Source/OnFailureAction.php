<?php

namespace Brasilcash\Gateway\Model\Adminhtml\Source;

class OnFailureAction implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => 'continue',
                'label' => __('Continue')
            ],
            [
                'value' => 'decline',
                'label' => __('Decline')
            ]
        ];
    }
}

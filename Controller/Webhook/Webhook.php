<?php

namespace Brasilcash\Gateway\Controller\Webhook;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Webhook extends Action
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue(); // Obtenha os dados do webhook POST aqui

        // Lógica para processar os dados do webhook
        // ...

        // Envie uma resposta de sucesso para o remetente do webhook
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData(['status' => 'success']);
        return $result;
    }
}

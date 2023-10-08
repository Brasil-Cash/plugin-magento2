<?php

namespace Brasilcash\Gateway\Services;

use Brasilcash\Gateway\Model\Ui\ConfigProvider;

class TransactionService extends HttpService
{
    public function enabledCreditCard() : bool {
        return (bool) $this->config->getValue('payment/' . ConfigProvider::CODE . '/payment_method_credit_card');
    }

    public function enabledPix() : bool {
        return (bool) $this->config->getValue('payment/' . ConfigProvider::CODE . '/payment_method_pix');
    }

    public function enabledBoleto() : bool {
        return (bool) $this->config->getValue('payment/' . ConfigProvider::CODE . '/payment_method_boleto');
    }
}

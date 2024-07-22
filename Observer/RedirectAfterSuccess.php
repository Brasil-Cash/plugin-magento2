<?php

namespace Brasilcash\Gateway\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class RedirectAfterSuccess implements ObserverInterface
{
    protected $redirect;
    protected $url;
    protected $response;
    protected $checkoutSession;

    public function __construct(
        RedirectInterface $redirect,
        UrlInterface $url,
        ResponseInterface $response,
        CheckoutSession $checkoutSession
    ) {
        $this->redirect = $redirect;
        $this->url = $url;
        $this->response = $response;
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(Observer $observer)
    {
        $brasilcashResponse = $this->checkoutSession->getBrasilcashResponseTransaction();

        if (isset($brasilcashResponse['threeDSecure']['url'])) {
            $this->redirect->redirect($this->response, $brasilcashResponse['threeDSecure']['url']);
        }
    }
}

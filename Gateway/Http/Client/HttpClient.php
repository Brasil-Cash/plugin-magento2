<?php

namespace Brasilcash\Gateway\Gateway\Http\Client;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use Magento\Checkout\Model\Session as CheckoutSession;

class HttpClient implements \Magento\Payment\Gateway\Http\ClientInterface
{
    /** @var Logger */
    private $logger;
    /** @var Curl */
    private $curl;
    /** @var CheckoutSession */
    private $checkoutSession;

    public function __construct(Logger $logger, Curl $curl, CheckoutSession $checkoutSession)
    {
        $this->logger = $logger;
        $this->curl = $curl;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @inheritDoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        foreach ($transferObject->getHeaders() as $key => $val) {
            $this->curl->addHeader($key, $val);
        }
        $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->curl->post($transferObject->getUri(), json_encode($transferObject->getBody()));
        $response = (array) json_decode($this->curl->getBody(), true);
        $status = $this->curl->getStatus();

        $this->logger->debug(
            [
                'request' => $transferObject->getBody(),
                'response' => $response,
                'code' => $status
            ]
        );

        $this->checkoutSession->setBrasilcashResponseTransaction($response);

        return $response;
    }
}

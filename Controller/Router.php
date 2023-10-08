<?php

namespace Brasilcash\Gateway\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Logger\Monolog;

/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ResponseInterface
     */
    private $response;
    private $logger;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param ResponseInterface $response
     */
    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->logger = new Monolog("debug");
    }

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $identifier = trim($request->getPathInfo(), '/');
        $method = $request->getMethod();

        if ($identifier === 'cartoes' && $method === 'GET') {
            $request->setModuleName('brasilcash');
            $request->setControllerName('cards');
            $request->setActionName('index');
        } else if ((bool) preg_match('/cartoes\/(\d+)/', $identifier, $matches) && $method === 'DELETE') { // cartoes/123
            $request->setModuleName('brasilcash');
            $request->setControllerName('cards');
            $request->setActionName('remove');
            $request->setParam('id', $matches[1]);
        } else if ($identifier === 'webhook' && $method === 'POST') {
            $request->setModuleName('brasilcash');
            $request->setControllerName('transactions');
            $request->setActionName('webhook');
            if (!$request->getHeader('X-Requested-With')) { // Required for POST route
                $request->getHeaders()->addHeaders(['X-Requested-With' => 'XMLHttpRequest']);
            }
            $webhookData = json_decode($request->getContent()); // Converte o JSON em um objeto stdClass
            $this->logger->info('WEBHOOK: ' . json_encode($webhookData));
            
            $request->setParam('body', $webhookData);
        } else {
            return null;
        }
        return $this->actionFactory->create(Forward::class, ['request' => $request]);
    }
}

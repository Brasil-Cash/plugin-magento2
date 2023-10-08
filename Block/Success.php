<?php
namespace Brasilcash\Gateway\Block;

use Brasilcash\Gateway\Setup\Patch\Data\InstallStatus;
use Magento\Framework\UrlInterface;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\View\Element\Template;
use Magento\Sales\Model\OrderRepository;
use Magento\Framework\Logger\Monolog;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Success extends \Magento\Sales\Block\Order\Totals
{
    protected $checkoutSession;
    protected $customerSession;
    protected $_orderFactory;
    protected $_order;
    protected $logger;

    public $pix;
    public $isPix = false;
    public $qrcode;
    
    public function __construct(
        Monolog $logger,
        OrderRepository $orderRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);

        $this->_orderFactory = $orderFactory;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
        $this->_order = $this->getOrder();

        // $order = $orderRepository->get($this->checkoutSession->getLastRealOrderId());
        $this->logger->info('Additional Information: ' . $this->checkoutSession->getLastRealOrderId());
        $additionalInformation = $this->_order->getPayment()->getAdditionalInformation();
        $this->logger->info('PIXXXX: ' . json_encode($additionalInformation));

        if (isset($additionalInformation['pix'])) {
            $this->isPix = true;
            $this->pix = $additionalInformation['pix'];
            $this->qrcode = new Qrcode(new QROptions([
                'eccLevel' => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version' => QRCode::VERSION_AUTO,
            ]));
        }

    }

    public function getOrder()
    {
        // Busque o pedido diretamente do contexto do observador. 
        return $this->checkoutSession->getLastRealOrder();
    }

    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }

}
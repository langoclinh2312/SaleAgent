<?php

namespace AHT\SaleAgent\Observer;

class ObserverOrder implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @param \AHT\SaleAgent\Model\SaleAgentFactory
     */
    private $_saleAgentFactory;

    /**
     * @param \AHT\SaleAgent\Model\SaleAgentRepository
     */
    private $_saleAgentRepository;

    public function __construct(
        \AHT\SaleAgent\Model\SaleAgentFactory $saleAgentFactory,
        \AHT\SaleAgent\Model\SaleAgentRepository $saleAgentRepository
    ) {
        $this->_saleAgentFactory = $saleAgentFactory;
        $this->_saleAgentRepository = $saleAgentRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderData = $observer->getData('order');
        $itemCollection = $orderData->getItemsCollection()->addAttributeToFilter('product_type', ['neq' => 'configurable']);

        foreach ($itemCollection as $item) {
            $itemId = $item->getId();
            $product = $item->getProduct();

            if (!empty($product->getSaleAgentId())) {
                $modelSaleAgent = $this->_saleAgentFactory->create();
                $modelSaleAgent->setEntityId($product->getSaleAgentId());
                $modelSaleAgent->setOrderId($item->getOrderId());
                $modelSaleAgent->setOrderItemId($itemId);
                $modelSaleAgent->setOrderItemSku($product->getSku());
                $modelSaleAgent->setOrderItemPrice($item->getPrice());
                $modelSaleAgent->setCommisionType($product->getCommissionType());
                $modelSaleAgent->setCommissionValue($product->getCommissionValue());
                $this->_saleAgentRepository->save($modelSaleAgent);
            }
        }
    }
}

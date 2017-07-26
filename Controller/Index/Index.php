<?php
/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Matej\bReviews\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resultJsonFactory = $objectManager->get('\Magento\Framework\Controller\Result\JsonFactory');
        $result = $resultJsonFactory->create();

        if ($this->getRequest()->isAjax()) {
            $reviewId = $this->getRequest()->getPostValue('productid');
            $statusId = $this->getRequest()->getPostValue('statusid');

            $storeManager       = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $customerSession    = $objectManager->get('\Magento\Customer\Model\Session');
            $resourceModel      = $objectManager->get('\Matej\bReviews\Model\ResourceModel\Reviews');

            $customerId = $customerSession->getCustomerId();
            $storeId = $storeManager->getStore()->getId();
            $resourceModel->saveLikeData($reviewId, $storeId, $customerId, $statusId);

            return $result->setData($reviewId);
        }
    }
}
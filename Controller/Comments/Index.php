<?php
/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Matej\bReviews\Controller\Comments;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    )
    {
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
            $reviewId           = $this->getRequest()->getPostValue('reviewid');
            $commentText        = $this->getRequest()->getPostValue('commenttext');
            $commentParent      = $this->getRequest()->getPostValue('parent');

            $storeManager       = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            $customerSession    = $objectManager->get('\Magento\Customer\Model\Session');
            $resourceModel      = $objectManager->get('\Matej\bReviews\Model\ResourceModel\ReviewComments');

            $customerId         = $customerSession->getCustomerId();
            $storeId            = $storeManager->getStore()->getId();

            $commentId = $resourceModel->saveCommentData($reviewId, $storeId, $customerId, $commentText, ($commentParent == 0) ? null : $commentParent);
            return $result->setData($commentId);
        }
    }
}
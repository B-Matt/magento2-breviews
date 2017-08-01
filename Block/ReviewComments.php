<?php
/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Matej\bReviews\Block;

class ReviewComments extends \Magento\Framework\View\Element\Template
{
    /*
     * Vars
     */
    protected $_reviewsFactory;

    /*
     * Constructor
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Matej\bReviews\Model\ReviewFactory $reviewsFactory
    )
    {
        parent::__construct($context);
        $this->_reviewsFactory = $reviewsFactory;
    }

    /*
     * Publics
     */
    public function getReviewComments($parent, $reviewId)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager   = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $resourceModel  = $objectManager->get('\Matej\bReviews\Model\ResourceModel\ReviewComments');

        $storeId        = $storeManager->getStore()->getId();
        $commentData    = $resourceModel->getReviewComments($reviewId, $storeId);

        $data           = [];
        $index          = [];

        foreach($commentData as $row) {
            $id          = $row['comment_id'];
            $parent_id   = $row['parent'] == NULL ? 'NULL' : $row['parent'];
            $data[$id]   = $row;
            $index[$parent_id][] = $id;
        }
        return $data;
    }

    public function getCustomerName()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        if ($customerSession->isLoggedIn()) {
            return $customerSession->getCustomer()->getName();
        } else {
            return "Guest";
        }
    }

    public function getCustomerNameById($id)
    {
        if($id == null)
            return 'Guest';

        return $this->getById($id);
    }

    public function getById($customerId)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $customerRegistry = $objectManager->get('\Magento\Customer\Model\CustomerRegistry');
        return $customerRegistry->retrieve($customerId)->getName();
    }
}
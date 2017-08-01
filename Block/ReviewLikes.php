<?php
/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Matej\bReviews\Block;

class ReviewLikes extends \Magento\Framework\View\Element\Template
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
    public function getReviewLikes($reviewId)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager   = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $resourceModel  = $objectManager->get('\Matej\bReviews\Model\ResourceModel\Reviews');

        $storeId = $storeManager->getStore()->getId();
        return $resourceModel->loadReviewLikes($reviewId, $storeId);;
    }

    public function getReviewDislikes($reviewId)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager   = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $resourceModel  = $objectManager->get('\Matej\bReviews\Model\ResourceModel\Reviews');

        $storeId = $storeManager->getStore()->getId();
        return $resourceModel->loadReviewDislikes($reviewId, $storeId);;
    }
}
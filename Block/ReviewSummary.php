<?php

/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Matej\bReviews\Block;

class ReviewSummary extends \Magento\Framework\View\Element\Template
{
    /*
     * Vars
     */
    public $_totalVotes = 0;

    /*
     * Constructor
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        parent::__construct($context);
    }

    /*
     * Publics
     */
    public function getProduct()
    {
        $coreRegistry = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Framework\Registry');
        return $coreRegistry->registry('current_product');
    }

    public function getRatingSummary($product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $reviewFactory = $objectManager->get('\Magento\Review\Model\ReviewFactory');
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $storeId = $storeManager->getStore()->getId();

        $reviewFactory->create()->getEntitySummary($product, $storeId);
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }

    public function getProductRating($product)
    {
        return ( $this->getRatingSummary($product) / 100 ) * 5;
    }

    public function getProductRatingPercent($productVotes)
    {
        return ( $productVotes / $this->_totalVotes ) * 100;
    }

    public function getProductRatingGroup($product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $reviewFactory = $objectManager->get('\Magento\Review\Model\Review');
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $arrRatings = [];

        $review = $reviewFactory->getCollection()
            ->addFieldToFilter('main_table.status_id', 1)
            ->addEntityFilter('product', $product->getId())
            ->addStoreFilter($storeManager->getStore()->getId())
            ->addFieldToSelect('review_id');

        $review->getSelect()->columns('detail.detail_id')->joinInner(
            ['vote' => $review->getTable('rating_option_vote')], 'main_table.review_id = vote.review_id', array('review_value' => 'vote.value')
        );

        $review->getSelect()->order('review_value DESC');
        $review->getSelect()->columns('count(vote.vote_id) as total_vote')->group('review_value');
        for ($i = 5; $i >= 1; $i--) {
            $arrRatings[$i]['value'] = 0;
        }
        foreach ($review as $_result) {
            $arrRatings[$_result['review_value']]['value'] = $_result['total_vote'];
            $this->_totalVotes += $_result['total_vote'];
        }
        return $arrRatings;
    }

    public function getBarOpacity($order)
    {
        $class = "five";
        switch($order) {
            case 5:
                $class = 'five';
                break;
            case 4:
                $class = 'four';
                break;
            case 3:
                $class = 'three';
                break;
            case 2:
                $class = 'two';
                break;
            case 1:
                $class = 'one';
                break;
        }
        return $class;
    }
}
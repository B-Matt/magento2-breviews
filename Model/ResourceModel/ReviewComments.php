<?php

/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Matej\bReviews\Model\ResourceModel;

class ReviewComments extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('review_comments', 'comment_id');
    }

    /*
     * Loading
     */
    public function getReviewComments($reviewId, $storeId)
    {
        $connection = $this->getConnection();
        $query = "SELECT comment_id, parent, text, customer_id FROM " . $this->getMainTable() . " WHERE store_id = '" . $storeId . "' AND review_id = '" . $reviewId . "' ORDER BY comment_id";
        return $connection->fetchAssoc($query);
    }

    /*
     * Inserts
     */
    public function saveCommentData($reviewID, $storeID, $customerID, $text, $parent)
    {
        $data = array('review_id' => $reviewID, 'store_id' => $storeID, 'customer_id' => $customerID, 'text' => $text, 'parent' => $parent);
        $this->getConnection()->insert($this->getMainTable(), $data);
        return $this->getConnection()->lastInsertId($this->getMainTable());
    }
}
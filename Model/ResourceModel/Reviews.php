<?php

/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Matej\bReviews\Model\ResourceModel;

class Reviews extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('review_likes', 'like_id');
    }

    /*
     * Loading
     */
    public function loadReviewLikes($reviewID, $storeID)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'COUNT(*)')
            ->where('review_id = ?', $reviewID)
            ->where('store_id = ?', $storeID)
            ->where('like_status = ?', 1);
        return (int)$this->getConnection()->fetchOne($select);
    }
    public function loadReviewDislikes($reviewID, $storeID)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'COUNT(*)')
            ->where('review_id = ?', $reviewID)
            ->where('store_id = ?', $storeID)
            ->where('like_status = ?', 2);
        return (int)$this->getConnection()->fetchOne($select);
    }
    //TODO: Reviews sorting by $sortID
    public function loadSortedReviews($sortID, $storeID, $productID)
    {
        $connection = $this->getConnection();
        $query  = "SELECT *,";
        $query .= "(SELECT COUNT(*) FROM review_likes WHERE (review_id = review.review_id) AND like_status = '" . $sortID . "' AND store_id = '" . $storeID . "')";
        $query .= "AS review_count FROM review WHERE status_id = '1' AND entity_pk_value = '" . $productID . "' ORDER BY review_count DESC";

        $select = $connection->query($query);
        return $this->getConnection()->fetchOne($select);
    }

    /*
     * Inserts
     */
    public function saveLikeData($reviewID, $storeID, $customerID, $status)
    {
        $data = array('review_id' => $reviewID, 'store_id' => $storeID, 'customer_id' => $customerID, 'like_status' => $status);
        $this->getConnection()->insert($this->getMainTable(), $data);
        return $this;
    }
}
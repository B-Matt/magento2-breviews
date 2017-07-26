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
    public function loadById($id)
    {
        $connection = $this->getConnection();
        $where = $connection->quoteInto("like_id = ?", $id);
        return $connection->fetchOne($connection->select()->from($this->getMainTable())->where($where));
    }
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
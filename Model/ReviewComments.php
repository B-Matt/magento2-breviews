<?php

/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Matej\bReviews\Model;

class Review extends \Magento\Framework\Model\AbstractModel implements TestInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'matej_breviews_reviewcomments';

    protected function _construct()
    {
        $this->_init('Matej\bReviews\Model\ResourceModel\ReviewComments');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
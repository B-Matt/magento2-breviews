<?php
namespace Matej\bReviews\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    public static function getReviewLikes($reviewId)
    {
        $block = \Magento\Framework\App\ObjectManager::getInstance()->create('Matej\bReviews\Block\ReviewLikes');
        return $block->getReviewLikes($reviewId);
    }

    public static function getReviewDislikes($reviewId)
    {
        $block = \Magento\Framework\App\ObjectManager::getInstance()->create('Matej\bReviews\Block\ReviewLikes');
        return $block->getReviewDislikes($reviewId);
    }
}
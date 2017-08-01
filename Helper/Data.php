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

    public static function getReviewComments($reviewId)
    {
        $block = \Magento\Framework\App\ObjectManager::getInstance()->create('Matej\bReviews\Block\ReviewComments');
        return $block->getReviewComments(NULL, $reviewId);
    }

    public static function getCustomerName()
    {
        $block = \Magento\Framework\App\ObjectManager::getInstance()->create('Matej\bReviews\Block\ReviewComments');
        return $block->getCustomerName();
    }

    public static function getCustomerNameById($id)
    {
        $block = \Magento\Framework\App\ObjectManager::getInstance()->create('Matej\bReviews\Block\ReviewComments');
        return $block->getCustomerNameById($id);
    }
}
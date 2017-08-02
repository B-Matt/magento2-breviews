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
    public function getReviewComments($reviewId)
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
        return [$data, $index];
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

    public function getHtml($parent_id = NULL, $reviewId)
    {
        $html = '';
        list($comments, $index) = $this->getReviewComments($reviewId);
        $arrayParent = $parent_id == NULL ? "NULL" : $parent_id;

        if (isset($index[$arrayParent])) {
            foreach ($index[$arrayParent] as $id) {
                $html .= '<li class="review-comments-list-' . (($parent_id % 2) == 0 ? ('white') : ('grey')) . '-layer">';
                $html .= '<span class="review-comment-author">Comment by <b>' . $this->getCustomerNameById($comments[$id]['customer_id']) . '</b> · <a class="review-comments-comment-button" href="javascript:void(0);" onclick="javascript:openCommentInput(this, false);" >Comment</a><a class="review-comments-collapse" href="javascript:void(0);" onclick="javascript:collapseComment(this);">[-]</a></span></span>';
                $html .= '<span class="review-comment-text">' . $comments[$id]['text'] . '</span>';
                $html .= '<div class="review-comments-input">';
                $html .= '<span>Comment Text: </span><br>';
                $html .= '<textarea rows="4" cols="50"></textarea>';
                $html .= sprintf('<button href="javascript:void(0);" onclick="javascript:submitReviewComment(this, \'%d\', \'%d\', \'%s\');" class="action submit primary">Submit</button>', $reviewId, $comments[$id]['comment_id'], $this->getCustomerName());
                $html .= '</div>';
                $html .= '<ul>';
                $html .= $this->getHtml($id, $reviewId);
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
        return $html;
    }
}
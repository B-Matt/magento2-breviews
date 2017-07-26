<?php
/**
 * Copyright © 2017 B-Matt. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Matej\bReviews\Block;

use Magento\Customer\Model\Context;
use Magento\Customer\Model\Url;

class Form extends \Magento\Framework\View\Element\Template
{

    /*
     * Inherited vars
     */
    protected $_reviewData = null;
    protected $productRepository;
    protected $_ratingFactory;
    protected $urlEncoder;
    protected $messageManager;
    protected $httpContext;
    protected $customerUrl;
    protected $jsLayout;

    /*
     * Class vars
     */

    /*
     * Constructors
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = []
    ) {
        // Inherited vars
        $this->urlEncoder           = $urlEncoder;
        $this->_reviewData          = $reviewData;
        $this->productRepository    = $productRepository;
        $this->_ratingFactory       = $ratingFactory;
        $this->messageManager       = $messageManager;
        $this->httpContext          = $httpContext;
        $this->customerUrl          = $customerUrl;

        // Class vars

        // Etc
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) ? $data['jsLayout'] : [];
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setAllowWriteReviewFlag(
            $this->httpContext->getValue(Context::CONTEXT_AUTH)
            || $this->_reviewData->getIsGuestAllowToWrite()
        );
        if (!$this->getAllowWriteReviewFlag()) {
            $queryParam = $this->urlEncoder->encode(
                $this->getUrl('*/*/*', ['_current' => true]) . '#review-form'
            );
            $this->setLoginLink(
                $this->getUrl(
                    'customer/account/login/',
                    [Url::REFERER_QUERY_PARAM_NAME => $queryParam]
                )
            );
        }

        $this->setTemplate('Matej_bReviews::form.phtml');
    }

    /*
     * Publics
     */
    public function getJsLayout()
    {
        return \Zend_Json::encode($this->jsLayout);
    }

    public function getProductInfo()
    {
        return $this->productRepository->getById(
            $this->getProductId(),
            false,
            $this->_storeManager->getStore()->getId()
        );
    }

    public function getAction()
    {
        return $this->getUrl(
            'review/product/post',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
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

    public function getRatings()
    {
        return $this->_ratingFactory->create()->getResourceCollection()->addEntityFilter(
            'product'
        )->setPositionOrder()->addRatingPerStoreName(
            $this->_storeManager->getStore()->getId()
        )->setStoreFilter(
            $this->_storeManager->getStore()->getId()
        )->setActiveFilter(
            true
        )->load()->addOptionToItems();
    }

    public function getRegisterUrl()
    {
        return $this->customerUrl->getRegisterUrl();
    }

    /*
     * Protected
     */
    protected function getProductId()
    {
        return $this->getRequest()->getParam('id', false);
    }
}
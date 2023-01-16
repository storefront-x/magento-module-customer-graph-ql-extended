<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace StorefrontX\CustomerGraphQlExtended\Helper\Oauth;

/**
 * OAuth View Helper for Controllers
 */
class Data
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**#@+
     * Consumer xpath settings
     */
    const XML_PATH_CUSTOMER_EXPIRATION_PERIOD = 'oauth/access_token_lifetime/customer';

    const CUSTOMER_EXPIRATION_PERIOD_DEFAULT = 3600;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get consumer expiration period value from system configuration in seconds
     *
     * @return int
     */
    public function getCustomerExpirationPeriod()
    {
        $seconds = (int)$this->_scopeConfig->getValue(self::XML_PATH_CUSTOMER_EXPIRATION_PERIOD);
        return $seconds > 0 ? $seconds : self::CUSTOMER_EXPIRATION_PERIOD_DEFAULT;
    }
}

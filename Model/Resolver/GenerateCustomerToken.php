<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Model\Resolver;

use Magento\Store\Model\StoreManagerInterface;

class GenerateCustomerToken
{
    private $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }
    /**
     * afterResolve
     * @SuppressWarnings("unused")
     * @param Magento\CustomerGraphQl\Model\Resolver\GenerateCustomerToken $subject
     * @param array $result
     * @return array
     */
    public function afterResolve($subject, array $result):array
    {
        if(isset($result['token'])) {
                if(!isset($_COOKIE['PHPSESSID'])) {
                        setcookie('PHPSESSID', session_id());
                }
                $result['phpSessionId'] = session_id();
                $storeCode = $this->storeManager->getStore()->getCode();
                setcookie($storeCode.':customer:token', $result['token']);
        }
        return $result;
    }
}

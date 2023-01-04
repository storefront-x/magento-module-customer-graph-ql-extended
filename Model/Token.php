<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Model;

/**
 * Class Token
 * @package StorefrontX\CustomerGraphQlExtended\Model
 */
class Token
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateHelper;


    /**
     * @param \StorefrontX\CustomerGraphQlExtended\Helper\Oauth\Data $dataHelper
     */
    public function __construct(
        \StorefrontX\CustomerGraphQlExtended\Helper\Oauth\Data $dataHelper
    ){
        $this->dataHelper = $dataHelper;
    }

    public function getToken(){

        $token = false;

        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        $authorizationBearer = '';

        if(isset($headers['Authorization'])) {
            $authorizationBearer = $headers['Authorization'];
        } else if(isset($headers['Authorization'])) {
            $authorizationBearer = $headers['Authorization'];
        } else {
            $authorizationBearer = "";
        }

        $authorizationBearerArr = explode(' ', $authorizationBearer);
        if(
            isset($authorizationBearerArr[0]) &&
            trim($authorizationBearerArr[0]) == 'Bearer' &&
            isset($authorizationBearerArr[1])
        ){
            $token = $authorizationBearerArr[1];
        }

        return $token;
    }

    /**
     * @param $tokenUpdatedAt
     * @return bool
     */
    public function isValidForTokenExchange($tokenUpdatedAt)
    {
        $expiry = $this->dataHelper->getCustomerExpirationPeriod()*3600;
        $currentTimestamp = $this->getDateHelper()->gmtTimestamp();
        $updatedTimestamp = $this->getDateHelper()->gmtTimestamp($tokenUpdatedAt);
        return $expiry > ($currentTimestamp - $updatedTimestamp);
    }

    /**
     * The getter function to get the new DateTime dependency
     *
     * @return \Magento\Framework\Stdlib\DateTime\DateTime
     *
     * @deprecated 100.0.6
     */
    private function getDateHelper()
    {
        if ($this->dateHelper === null) {
            $this->dateHelper = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Stdlib\DateTime\DateTime::class);
        }
        return $this->dateHelper;
    }

}

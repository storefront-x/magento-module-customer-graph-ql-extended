<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Model;

use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Token
 * @package StorefrontX\CustomerGraphQlExtended\Model
 */
class Token
{
    /** @var DateTime */
    private $dateTime;

    /**
     * @param \StorefrontX\CustomerGraphQlExtended\Helper\Oauth\Data $dataHelper
     * @param DateTime $dateTime
     */
    public function __construct(
        \StorefrontX\CustomerGraphQlExtended\Helper\Oauth\Data $dataHelper,
        DateTime $dateTime
    ){
        $this->dataHelper = $dataHelper;
        $this->dateTime = $dateTime;
    }

    public function getToken(){

        $token = false;

        $authorizationBearer = "";

        if(isset($_SERVER["HTTP_AUTHORIZATION"])) {
            $authorizationBearer = $_SERVER["HTTP_AUTHORIZATION"];
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
        $currentTimestamp = $this->dateTime->gmtTimestamp();
        $updatedTimestamp = $this->dateTime->gmtTimestamp($tokenUpdatedAt);
        return $expiry > ($currentTimestamp - $updatedTimestamp);
    }
}

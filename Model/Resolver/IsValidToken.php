<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Integration\Model\Oauth\TokenFactory as TokenModelFactory;
use StorefrontX\CustomerGraphQlExtended\Model\Token;

class IsValidToken
{

    /** @var TokenModelFactory */
    private $tokenFactory;

    /** @var Token */
    private $token;

    /**
     * @param TokenModelFactory $tokenFactory
     * @param Token $token
     */
    public function __construct(
        TokenModelFactory $tokenFactory,
        Token $token
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->token = $token;
    }

    /**
     * Token validation
     *
     * @return bool
     */
    public function validation()
    {
        $token = $this->token->getToken();
        $tokenUpdatedAt = $this->tokenFactory->create()->loadByToken($token)->getCreatedAt();

        $isValidExpirationTime = $this->token->isValidForTokenExchange($tokenUpdatedAt);

        if ($isValidExpirationTime && $tokenUpdatedAt) {
            return true;
        }
        return false;
    }

}

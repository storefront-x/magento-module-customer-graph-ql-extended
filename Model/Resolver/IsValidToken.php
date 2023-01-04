<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Integration\Model\Oauth\TokenFactory as TokenModelFactory;
use StorefrontX\CustomerGraphQlExtended\Model\Token;

class IsValidToken implements ResolverInterface
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
     * @inheritdoc
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $token = $this->token->getToken();

        $tokenUpdatedAt = $this->tokenFactory->create()->loadByToken($token)->getCreatedAt();

        $isValidToken = $this->token->isValidForTokenExchange($tokenUpdatedAt);

        $tokenMessage = null;
        $statusCode = null;

        if (!$isValidToken) {
            $tokenMessage = __("You are not logged in, please login.");
            $statusCode = __("HTTP 401");
        }

        return [
            "isValidToken" => $isValidToken,
            "validationMessage" => $tokenMessage,
            "statusCode" => $statusCode
        ];
    }

}

<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Plugin\Controller;

use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\App\Request\Http;
use Magento\GraphQl\Controller\HttpRequestProcessor;
use StorefrontX\CustomerGraphQlExtended\Controller\HttpRequestValidator\AuthorizationValidator;

class HttpRequestProcessorPlugin
{
    /**
     * @param AuthorizationValidator $authorizationValidator
     */
    public function __construct(
        AuthorizationValidator $authorizationValidator
    ) {
        $this->authorizationValidator = $authorizationValidator;
    }
    
    /**
     * Additional authorization code validation
     *
     * @param Http $request
     * @return callable
     */
    public function aroundValidateRequest(HttpRequestProcessor $subject, callable $proceed,Http $request) : callable
    {
        $this->authorizationValidator->validateAuthorizationCode($request);
        
        return $proceed;
    }
}

<?php

declare(strict_types=1);

namespace StorefrontX\CustomerGraphQlExtended\Controller\HttpRequestValidator;

use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\GraphQl\Controller\HttpRequestValidatorInterface;
use StorefrontX\CustomerGraphQlExtended\Model\Resolver\IsValidToken;

class AuthorizationValidator
{

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param IsValidToken $validToken
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        IsValidToken $validToken
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->validToken = $validToken;
    }

    /**
     * Validation authorization code
     * 
     * @param HttpRequestInterface $request
     * @return void
     * @throws GraphQlAuthorizationException
     */
    public function validateAuthorizationCode(HttpRequestInterface $request)
    {
        $headerInfo = $request->getHeader("Authorization");

        if ($this->isNeedNodeValidation($request) && $headerInfo){
            $validation = $this->validToken->validation();

            if (!$validation) {
                throw new GraphQlAuthorizationException(
                    __('HTTP 401: You are not logged in, please login.')
                );
            }
        }
    }

    /**
     * @param $request
     * @return bool
     */
    public function isNeedNodeValidation($request){

        $graphqlPublicEndpoint=$this->scopeConfig->getValue('magexo_logger/graphql/permitted_endpoint');
        $publicEndpoint = explode(";", $graphqlPublicEndpoint);

        $queryContent = json_decode($request->getContent(),true);
        $query = trim(preg_replace('/\s\s+/', ' ', $queryContent['query']));
        preg_match_all('/(\w+)(?=\s?[{(])/m',$query,$queryType);

        foreach (reset($queryType) as $type){
            if (in_array($type,$publicEndpoint)){
                return false;
            }
        }
        return true;
    }
}

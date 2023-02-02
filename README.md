# Customer GraphQl Extended
Module for Magento 2

**Customer GraphQl Extended** Added token validation for all endpoints.
If is neccesaairy to escape this additional endpoint validation,
it should be specified endpoints in this constant
oauth/access_token_lifetime/customer.
For example removeCouponFromCart, setShippingMethodsOnCart ...

Confirmation subscriber for newsletter endpoint
mutation{
    confirmSubscribeEmailToNewsletter(
        subscriber_id:"*"
        subscriber_confirm_code:"*******"
    )
    {
        status
    }
}

## License

The module is licensed under the MIT license.



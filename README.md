# Customer GraphQl Extended
Module for Magento 2

**Customer GraphQl Extended** Extends mutation for generateCustomerToken with phpSessionId and sets cookie `${store_code}:customer:token` with retrieved token after login.

SFX uses phpSessionId to set cookie and does not need to login user again through native Magento FE.
So, customer logs only once and can access both SFX and Magento sections requiring login.

## License

The module is licensed under the MIT license.



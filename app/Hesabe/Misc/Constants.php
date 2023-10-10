<?php

namespace App\Hesabe\Misc;

/**
 * This class is used for defining constant.
 *
 * @author Hesabe
 */
class Constants
{
    public const VERSION = "2.0";

    //Payment API URL
    // public const PAYMENT_API_URL = "https://sandbox.hesabe.com";
    public const PAYMENT_API_URL = "https://api.hesabe.com";

    // Get below values from Merchant Panel, Profile section
    public const ACCESS_CODE = "87f7a861-9cb3-41e7-aa74-2e7e0b22d351";
    public const MERCHANT_SECRET_KEY = "gq6JWP7kZYmN85A61mE8V32yb14B9XnM";
    public const MERCHANT_IV = "ZYmN85A61mE8V32y";
    public const MERCHANT_CODE = "86721223";

    // This URL are defined by you to get the response from Payment Gateway after success and failure
    public const RESPONSE_URL = 'https://realestate.alkhulaifitrading.com/admin/payment/success';
    public const FAILURE_URL = 'https://realestate.alkhulaifitrading.com/admin/payment/failure';

    //Codes
    public const SUCCESS_CODE = 200;
    public const AUTHENTICATION_FAILED_CODE = 501;
}

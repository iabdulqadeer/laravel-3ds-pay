<?php

namespace Iabdulqadeer\ThreeDSPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void noop()
 *
 * @see \Iabdulqadeer\ThreeDSPay\ThreeDSPay
 */
class ThreeDSPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'three-ds-pay';
    }
}

<?php

class ACMS_GET_Touch_CartStock extends ACMS_GET
{
    function get()
    {
        return config('shop_stock_change') == 'on' ? $this->tpl : false;
    }
}

<?php

class ACMS_GET_Touch_NotCartStock extends ACMS_GET
{
    function get()
    {
        return config('shop_stock_change') == 'on' ? false : $this->tpl;
    }
}

<?php

class ACMS_GET_Touch_Unlogin extends ACMS_GET
{
    function get()
    {
        return !SID ? $this->tpl : false;
    }
}

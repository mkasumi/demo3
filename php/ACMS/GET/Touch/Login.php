<?php

class ACMS_GET_Touch_Login extends ACMS_GET
{
    function get()
    {
        return (!!SID && !RVID) ? $this->tpl : false;
    }
}

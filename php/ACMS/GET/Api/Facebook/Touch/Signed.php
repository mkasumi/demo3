<?php

class ACMS_GET_Api_Facebook_Touch_Signed extends ACMS_GET_Api_Facebook
{
    function touch()
    {
        return $this->signed_data !== ACMS_GET_Api_Facebook::REQUEST_IS_NOT_SIGNED ? $this->tpl : '';
    }
}

<?php

class ACMS_GET_Api_Facebook_Touch_NotAdmin extends ACMS_GET_Api_Facebook
{
    function touch()
    {
        return isset($this->signed_data['page']['admin']) && $this->signed_data['page']['admin'] !== true ? $this->tpl : '';
    }
}

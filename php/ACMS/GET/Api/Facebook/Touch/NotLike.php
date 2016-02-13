<?php

class ACMS_GET_Api_Facebook_Touch_NotLike extends ACMS_GET_Api_Facebook
{
    function touch()
    {
        return isset($this->signed_data['page']['liked']) && $this->signed_data['page']['liked'] !== true ? $this->tpl : '';
    }
}

<?php

class ACMS_GET_Touch_NotAdminActionBox extends ACMS_GET
{
    function get()
    {
        return ('on' != config('admin_action_box') ) ? $this->tpl : false;
    }
}

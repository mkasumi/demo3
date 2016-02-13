<?php

class ACMS_GET_Admin_Top extends ACMS_GET_Admin
{
    function get()
    {
        if ( 'top' <> ADMIN ) return false;
        $Tpl    = new Template($this->tpl, new ACMS_Corrector());

        return $Tpl->get();
    }
}

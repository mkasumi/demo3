<?php

class ACMS_GET_Touch_SessionIsCompilation extends ACMS_GET
{
    var $_scope = array(
        'bid'   => 'global',
    );

    function get()
    {
        return ( isSessionEditor() && sessionWithCompilation($this->bid) ) ? $this->tpl : false;
    }
}

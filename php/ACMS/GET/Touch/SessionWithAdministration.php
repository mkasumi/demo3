<?php

class ACMS_GET_Touch_SessionWithAdministration extends ACMS_GET
{
    var $_scope = array(
        'bid'   => 'global',
    );

    function get()
    {
        return sessionWithAdministration($this->bid) ? $this->tpl : false;
    }
}

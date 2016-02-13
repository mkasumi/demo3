<?php

class ACMS_GET_Touch_SessionWithSubscription extends ACMS_GET
{
    var $_scope = array(
        'bid'   => 'global',
    );

    function get()
    {
        return sessionWithSubscription($this->bid) ? $this->tpl : false;
    }
}

<?php

class ACMS_GET_Touch_NotApproval extends ACMS_GET
{
    function get()
    {
        return enableApproval(BID) ? false : $this->tpl;
    }
}

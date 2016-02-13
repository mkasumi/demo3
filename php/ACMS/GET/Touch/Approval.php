<?php

class ACMS_GET_Touch_Approval extends ACMS_GET
{
    function get()
    {
        return enableApproval(BID) ? $this->tpl : false;
    }
}

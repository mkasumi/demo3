<?php

class ACMS_GET_Touch_ApprovalRequest extends ACMS_GET
{
    function get()
    {
        return sessionWithApprovalRequest(BID) ? $this->tpl : false;
    }
}

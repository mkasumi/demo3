<?php

class ACMS_GET_Touch_NotApprovalRejectRequest extends ACMS_GET
{
    function get()
    {
        return sessionWithApprovalRejectRequest(BID) ? false : $this->tpl;
    }
}

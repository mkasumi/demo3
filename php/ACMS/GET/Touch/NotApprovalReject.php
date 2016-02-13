<?php

class ACMS_GET_Touch_NotApprovalReject extends ACMS_GET
{
    function get()
    {
        return sessionWithApprovalReject(BID) ? false : $this->tpl;
    }
}

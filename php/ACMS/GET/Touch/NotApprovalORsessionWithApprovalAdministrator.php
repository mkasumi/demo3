<?php

class ACMS_GET_Touch_NotApprovalORsessionWithApprovalAdministrator extends ACMS_GET
{
    function get()
    {
        return (!enableApproval(BID) || sessionWithApprovalAdministrator(BID)) ? $this->tpl : false;
    }
}


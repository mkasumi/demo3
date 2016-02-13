<?php

class ACMS_GET_Touch_NotsessionWithApprovalAdministrator extends ACMS_GET
{
    function get()
    {
        if ( enableApproval(BID) && !sessionWithApprovalAdministrator(BID) ) {
            return $this->tpl;
        } else {
            return false;
        }
    }
}

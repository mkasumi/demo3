<?php

class ACMS_GET_Touch_RevisionAdministrator extends ACMS_GET
{
    function get()
    {
        if ( 0
            || !enableApproval(BID)
            || sessionWithApprovalAdministrator(BID)
        ) {
            return $this->tpl;
        }
        return false;
    }
}

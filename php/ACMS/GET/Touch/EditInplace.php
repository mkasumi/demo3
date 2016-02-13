<?php

class ACMS_GET_Touch_EditInplace extends ACMS_GET
{
    function get()
    {
        return ('on' == config('entry_edit_inplace_enable') && 'on' == config('entry_edit_inplace') && ( !enableApproval() || sessionWithApprovalAdministrator() ) && !RVID) ? $this->tpl : false;
    }
}

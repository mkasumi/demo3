<?php

class ACMS_GET_Touch_NotEditInplace extends ACMS_GET
{
    function get()
    {
        return (('on' != config('entry_edit_inplace_enable') && 'on' == config('entry_edit_inplace')) || ( enableApproval() && !sessionWithApprovalAdministrator() ) ) ? $this->tpl : false;
    }
}

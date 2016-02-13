<?php

class ACMS_GET_Admin_Workflow_Index extends ACMS_GET_Admin_Edit
{
    function edit(& $Tpl)
    {
        if ( !sessionWithEnterpriseAdministration() ) die(); 
        $Workflow =& $this->Post->getChild('workflow');

        if ( $Workflow->isNull() ) {
            $Workflow->overload(loadWorkflow(BID));
        }
        return $Workflow;
    }
}

<?php

class ACMS_GET_Links extends ACMS_GET
{
    function get()
    {
        if ( !$vals = configArray('links_value') ) return '';
        $Tpl    = new Template($this->tpl, new ACMS_Corrector());
        $this->buildModuleField($Tpl);
        
        $labels = configArray('links_label');
        foreach ( $vals as $i => $val ) {
            $Tpl->add('loop', array(
                'url' => $val,
                'name' => $labels[$i],
            ));
        }
        return setGlobalVars($Tpl->get());
    }
}

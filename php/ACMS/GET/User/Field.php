<?php

class ACMS_GET_User_Field extends ACMS_GET
{
    var $_scope = array(
        'uid'   => 'global',
    );

    function get()
    {
        if ( !$this->uid ) return '';
        if ( !$row = ACMS_RAM::user($this->uid) ) return '';

        $status = ACMS_RAM::userStatus($this->uid);
        if (!sessionWithAdministration() and 'close' === $status) return '';

        $Tpl    = new Template($this->tpl, new ACMS_Corrector());
        $this->buildModuleField($Tpl);

        $Field  = loadUserField($this->uid);
        foreach ( $row as $key => $val ) {
            $Field->setField(preg_replace('@^user_@', '', $key), $val);
        }
        $Tpl->add(null, $this->buildField($Field, $Tpl));
        
        return $Tpl->get();
    }
}

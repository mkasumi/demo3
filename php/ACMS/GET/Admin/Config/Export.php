<?php

class ACMS_GET_Admin_Config_Export extends ACMS_GET_Admin_Config
{
    function get()
    {
        if ( 'config_export' <> ADMIN ) return false;
//        if ( !sessionWithAdministration() ) return false;

        $Tpl    = new Template($this->tpl, new ACMS_Corrector());

        if ( !($rid = intval($this->Get->get('rid'))) ) { $rid = null; }
        if ( !($mid = intval($this->Get->get('mid'))) ) { $mid = null; }

        $vars   = $this->buildField($this->Post, $Tpl);
        $vars['indexUrl']       = $this->getIndexUrl($rid, $mid);
        $vars['shortcutUrl']    = acmsLink(array(
                'bid'   => BID,
                'admin' => 'shortcut_edit',
                'query' => array(
                    'action' => 'Config',
                    'admin'  => ADMIN,
                    'edit'   => 'add',
                    'step'   => 'reapply',
                    'rid'   => $rid,
                    'mid'   => $mid,
                )
        ));
        $Tpl->add(null, $vars);

        return $Tpl->get();
    }
}

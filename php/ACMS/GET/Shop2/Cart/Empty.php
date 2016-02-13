<?php

class ACMS_GET_Shop2_Cart_Empty extends ACMS_GET_Shop2
{
    function get()
    {	
        $this->initVars();

        $step   = $this->Post->get('step', 'apply');
        $bid    = BID;

        if ( $step == 'result' ) {
            if ( !empty($_SESSION[$this->cname.$bid]) ) {
                unset($_SESSION[$this->cname.$bid]);
            }
        }

        return '';
    }
}
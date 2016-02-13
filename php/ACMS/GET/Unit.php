<?php

class ACMS_GET_Unit extends ACMS_GET
{
    function buildUnit(& $Column, & $Tpl, $rootBlock = array(), $preAlign = null, $renderGroup = true)
    {
        return ACMS_GET_Entry::buildColumn($Column, $Tpl, $rootBlock, $preAlign, $renderGroup);
    }
}

<?php

class ACMS_GET_Entry_List extends ACMS_GET_Entry_Summary
{
    function initVars()
    {
        return array(
            'order'            => $this->order ? $this->order : config('entry_list_order'),
            'limit'            => intval(config('entry_list_limit')),
            'offset'           => intval(config('entry_list_offset')),
            'indexing'         => config('entry_list_indexing'),
            'secret'           => config('entry_list_secret'),
            'newtime'          => config('entry_list_newtime'),
            'unit'             => config('entry_list_unit'),
            'notfound'         => config('mo_entry_list_notfound'),
            'notfoundStatus404'=> config('entry_list_notfound_status_404'),
            'noimage'          => config('entry_list_noimage'),
            'imageX'           => intval(config('entry_list_image_x')),
            'imageY'           => intval(config('entry_list_image_y')),
            'imageTrim'        => config('entry_list_image_trim'),
            'imageZoom'        => config('entry_list_image_zoom'),
            'imageCenter'      => config('entry_list_image_center'),
            'pagerDelta'       => config('entry_list_pager_delta'),
            'pagerCurAttr'     => config('entry_list_pager_cur_attr'),
            'hiddenCurrentEntry'    => config('entry_list_hidden_current_entry'),
            'loop_class'            => config('entry_list_loop_class'),
        );
    }
}

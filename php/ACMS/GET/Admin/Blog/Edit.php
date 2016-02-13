<?php

class ACMS_GET_Admin_Blog_Edit extends ACMS_GET_Admin_Edit
{
    function edit(& $Tpl)
    {
        $Blog   =& $this->Post->getChild('blog');
        $Field  =& $this->Post->getChild('field');
        $Config =& $this->Post->getChild('config');

        if ( $Blog->isNull() ) {
            if ( 'insert' <> $this->edit ) {
                $Blog->overload(loadBlog(BID));
                $Field->overload(loadBlogField(BID));
                $Config->overload(loadConfig(BID));
            } else {
                //---------
                // default
                $Blog->set('domain', DOMAIN);
                $Blog->set('status', 'open');
                $Blog->set('indexing', 'on');
            }
        }

        return true;
    }
}

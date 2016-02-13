<?php

class ACMS_GET_Blog_ChildList extends ACMS_GET
{
    var $_scope = array(
        'bid'   => 'global',
    );
    function get()
    {
        $Tpl    = new Template($this->tpl, new ACMS_Corrector());
        $this->buildModuleField($Tpl);
        
        $DB     = DB::singleton(dsn());
        $SQL    = SQL::newSelect('blog');
        $SQL->addWhereOpr('blog_parent', $this->bid);
        $SQL->addWhereOpr('blog_indexing', 'on');
        ACMS_Filter::blogOrder($SQL, config('blog_child_list_order'));
        ACMS_Filter::blogStatus($SQL);
        if ( !empty($this->keyword) ) {
            ACMS_Filter::blogKeyword($SQL, $this->keyword);
        }
        if ( !empty($this->Field) ) {
            ACMS_Filter::blogField($SQL, $this->Field);
        }
        $SQL->setLimit(intval(config('blog_child_list_limit')));
        $q  = $SQL->get(dsn());

        $loopClass  = config('blog_child_list_loop_class');
        
        if ( $DB->query($q, 'fetch') and ( $row = $DB->fetch($q)) ) {
            $i      = 0;
            $j      = $DB->affected_rows();
            do {
                $i++;
                $bid    = intval($row['blog_id']);

                //-------
                // field
                $Field  = loadBlogField($bid);
                foreach ( $row as $key => $val ) {
                    $Field->setField(substr($key, 5), $val);
                }
                $Field->set('url', acmsLink(array(
                    'bid'   => $bid,
                )));
                $Field->set('blog:loop.class', $loopClass);

                //------
                // glue
                if ( $i !== $j ) {
                    $Tpl->add('glue');
                }

                $Tpl->add('blog:loop', $this->buildField($Field, $Tpl));
            } while ( !!($row = $DB->fetch($q)) );
        }
        
        $currentBlog    = loadBlogField($this->bid);
        $currentBlog->overload(loadBlog($this->bid));
        $currentBlog->set('url', acmsLink(array(
            'bid'   => $this->bid,
        )));
        $Tpl->add('currentBlog', $this->buildField($currentBlog, $Tpl));
        
        return $Tpl->get();
    }
}

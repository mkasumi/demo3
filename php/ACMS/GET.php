<?php

class ACMS_GET
{
    var $tpl    = null;

    var $bid    = null;
    var $uid    = null;
    var $cid    = null;
    var $eid    = null;
    var $keyword= null;
    var $tag    = null;
    var $tags   = array();
    var $field  = null;
    var $Field  = null;
    var $start  = null;
    var $end    = null;
    var $alt    = null;

    var $step   = null;
    var $action = null;
    
    var $squareSize = null;

    /**
     * @var Field
     */
    var $Q;
    /**
     * @var Field
     */
    var $Get;
    /**
     * @var Field
     */
    var $Post;

    var $_scope = array();
    var $_axis  = array(
        'bid'   => 'self',
        'cid'   => 'self',
    );

    var $mid  = null;
    var $mbid = null;

    function ACMS_GET($tpl, $acms, $scope, $axis, $Post, $mid=null, $mbid=null, $identifier=null, $aryMultiAcms=null, $showField=false)
    {
        $this->tpl  = $tpl;
        $this->Post = new Field_Validation($Post, true);
        $this->Get  = new Field(Field::singleton('get'));
        $this->Q    = new Field(Field::singleton('query'), true);
        $this->identifier   = $identifier;
        $this->showField    = $showField;

        //-------
        // scope
        $Arg    = parseAcmsPath($acms);
        $this->Q->set('bid', $Arg->get('bid', $this->Q->get('bid')));
        foreach ( array(
            'cid', 'eid', 'uid', 'keyword', 'tag', 'field', 'start', 'end', 'page', 'order'
        ) as $key ) {
            $isGlobal   = ('global' == (!empty($scope[$key]) ? $scope[$key] : (!empty($this->_scope[$key]) ? $this->_scope[$key] : 'local')));
            if ( 'field' == $key ) {
                $Field  = $this->Q->getChild('field');
                if ( !$isGlobal or $Field->isNull() ) {
                    $this->Q->addChild('field', $Arg->getChild('field'));
                }
            } else if ( !$isGlobal or !$this->Q->get($key) ) {
                $val    = $Arg->getArray($key);
                if ( ('page' == $key) and (1 > $val[0]) ) $val[0] = 1;
                $this->Q->set($key, array_shift($val));
                foreach ( $val as $argV ) {
                    $this->Q->add($key, $argV);
                }
            } else if ( $isGlobal && ( 0
                || ( $key == 'start' && $this->Q->get($key) == '1000-01-01 00:00:00' )
                || ( $key == 'end' && $this->Q->get($key) == '9999-12-31 23:59:59' )
            ) ) {
                $val = $Arg->getArray($key);
                $this->Q->set($key, array_shift($val));
                foreach ( $val as $argV ) {
                    $this->Q->add($key, $argV);
                }
            }
        }

        if ( !$this->Q->isNull('bid') ) {
            $this->bid  = intval($this->Q->get('bid'));
        }
        if ( !$this->Q->isNull('cid') ) {
            $this->cid  = intval($this->Q->get('cid'));
        }
        if ( !$this->Q->isNull('eid') ) {
            $this->eid  = intval($this->Q->get('eid'));
        }
        if ( !$this->Q->isNull('uid') ) {
            $this->uid  = intval($this->Q->get('uid'));
        }
        if ( is_array($aryMultiAcms) ) {
            foreach ( $aryMultiAcms as $k => $v ) {
                $isGlobal_ = ('global' == (!empty($scope[$k]) ? $scope[$k] : (!empty($this->_scope[$k]) ? $this->_scope[$k] : 'local')));
                if ( !$isGlobal_ or !$this->Q->get($k) ) $this->{$k} = $v;
            }
        }

        $keyword    = $this->Q->get('keyword');
        $qkeyword   = $this->Get->get(KEYWORD_SEGMENT);
        if ( 1
            && !empty($qkeyword)
            && config('query_keyword') == 'on'
            && ('global' == (!empty($scope['keyword']) ? $scope['keyword'] : (!empty($this->_scope['keyword']) ? $this->_scope['keyword'] : 'local')))
        ) {
            $keyword = $this->Get->get(KEYWORD_SEGMENT);
        }

        $this->keyword  = $keyword;
        $this->start    = $this->Q->get('start');
        $this->end      = $this->Q->get('end');
        $this->page     = $this->Q->get('page');
        $this->order    = $this->Q->get('order');

        $this->tag      = join('/', $this->Q->getArray('tag'));
        $this->tags     = $this->Q->getArray('tag');
        $this->Field    =& $this->Q->getChild('field');
        $this->field    = $this->Field->serialize();

        //------
        // axis
        foreach ( array('bid', 'cid') as $key ) {
            if ( !array_key_exists($key, $axis) ) continue;
            $this->_axis[$key]  = $axis[$key];
        }

        $this->mid  = $mid;
        $this->mbid = $mbid;
    }

    function blogAxis()
    {
        return $this->_axis['bid'];
    }

    function categoryAxis()
    {
        return $this->_axis['cid'];
    }

    function fire()
    {
        //----------------
        // module link
        if ( isSessionAdministrator() ) {
            $className  = str_replace(array('ACMS_GET_', 'ACMS_User_GET_'), '', get_class($this));
            $config     = 'config_'.strtolower(preg_replace('@(?<=[a-zA-Z0-9])([A-Z])@', '-$1', $className));
            $bid        = !empty($this->mbid) ? $this->mbid : BID;

            $url = acmsLink(array(
                'bid'   => $bid,
                'admin' => $config,
                'query' => array(
                    'mid'   => $this->mid,
                    'rid'   => RID,
                ),
            ));

            $this->tpl = str_replace(array(
                '{admin_module_bid}',
                '{admin_module_mid}',
                '{admin_module_url}',
                '{admin_module_name}',
                '{admin_module_identifier}',
            ), array(
                $bid,
                $this->mid,
                $url,
                $className,
                $this->identifier,
            ), $this->tpl);

            $this->tpl = preg_replace('@<!--[\t 　]*(BEGIN|END)[\t 　]module_setting[\t 　]-->@', '', $this->tpl);
        }

        //----------------
        // execute & hook
        if (HOOK_ENABLE) {
            $Hook = ACMS_Hook::singleton();
            $Hook->call('beforeGetFire', array(&$this->tpl, $this));
            $rv = $this->get();
            $Hook->call('afterGetFire', array(&$rv, $this));
            return $rv;
        } else {
            return $this->get();
        }
    }

    function get()
    {
        return false;
    }

    function buildModuleField(& $Tpl)
    {
        if ( $this->mid && $this->showField ) {
            $vars = $this->buildField(loadModuleField($this->mid), $Tpl, 'moduleField');
            $Tpl->add('moduleField', $vars);
        }
    }

    function buildDate($datetime, & $Tpl, $block=array(), $prefix='date#')
    {
        if ( !is_numeric($datetime) ) $datetime = strtotime($datetime);

        $block  = empty($block) ? array() : (is_array($block) ? $block : array($block));
        $w  = date('w', $datetime);
        $weekPrefix = $prefix === 'date#' ? 'week#'
                                          : str_replace('date', 'week', $prefix);
        $Tpl->add(array_merge(array($weekPrefix.$w), $block));

        $formats = array(
            'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z',
            'W',
            'F', 'm', 'M', 'n', 't',
            'L', 'o', 'Y', 'y',
            'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u',
            'e',
            'I', 'O', 'P', 'T', 'Z',
            'c', 'r', 'U',
        );
        $vars   = array();

        //--------
        // format
        $combined   = implode('__', $formats);
        $formatted  = explode('__', date($combined, $datetime));
        foreach ( $formatted as $p => $val ) {
            $c = $formats[$p];
            $vars[$prefix.$c] = $val;
        }
        $vars[$prefix]  = date('Y-m-d H:i:s', $datetime);

        $vars[$prefix.'week']   = config('week_label', '', intval($w));
        return $vars;
    }

    function buildField($Field, & $Tpl, $block=array(), $scp=null)
    {
        $block  = !empty($block) ? (is_array($block) ? $block : array($block)) : array();
        $vars   = array();
        $fds    = $Field->listFields(true);

        $isSearch   = ('FIELD_SEARCH' == strtoupper(get_class($Field))) ? true : false;

        //-------
        // group
        $mapGroup   = array();
        foreach ( $Field->listFields() as $fd ) {
            if ( preg_match('/^@(.*)$/', $fd, $match) ) {
                $groupName  = $match[1];
                $mapGroup[$groupName]   = $Field->getArray($fd);
            }
        }

        foreach ( $mapGroup as $groupName => $aryFd ) {
            $data   = array();

            for ( $i=0; true; $i++ ) {
                $row        = array();
                $isExists   = false;
                foreach ( $aryFd as $fd ) {
                    $isExists   |= $Field->isExists($fd, $i);
                    $row[$fd]   = $Field->get($fd, '', $i);
                }
                if ( !$isExists ) { break; }
                if ( !join('', $row) ) { continue; }
                $data[] = $row;
            }

            foreach ( $data as $i => $row ) {
                $_vars      = array();
                $loopblock  = array_merge(array($groupName.':loop'), $block);

                //-----------
                // validator         
                if ( method_exists($Field, 'isValid') ) {
                    foreach ( $row as $fd => $kipple ) {
                        foreach ( $Field->getMethods($fd) as $method ) {
                            if ( !$val = intval($Field->isValid($fd, $method, $i)) ) {
                                foreach ( array('validator', 'v') as $v ) {
                                    $key    = $fd.':'.$v.'#'.$method;
                                    $_vars[$key] = $val;
                                    $Tpl->add(array_merge(array($key), $loopblock), array($key => $val));
                                }
                            }
                        }
                    }
                }

                //-------
                // value
                foreach ( $row as $key => $value ) {
                    if ( $value !== '' ) {
                        $_vars[$key]    = $value;
                        $_vars[$key.':checked#'.$value]     = config('attr_checked');
                        $_vars[$key.':selected#'.$value]    = config('attr_selected');
                    }
                }

                //---
                // n
                $_vars['i']   = $i;

                if ( !empty($i) ) {
                    $Tpl->add(array_merge(array('glue'), $loopblock));
                    $Tpl->add(array_merge(array($key.':glue'), $loopblock));
                }
                $Tpl->add($loopblock, $_vars);
            }
        }

        $data   = array();
        foreach ( $fds as $fd ) {
            if ( !$aryVal = $Field->getArray($fd) ) $Tpl->add(array_merge(array($fd.':null'), $block));
            $data[$fd]  = $aryVal;
            if ( $isSearch ) {
                $data[$fd.'@connector'] = $Field->getConnector($fd, null);
                $data[$fd.'@operator']  = $Field->getOperator($fd, null);
            }
            if ( !method_exists($Field, 'isValid') ) continue;
            if ( !$val = intval($Field->isValid($fd)) ) {
                foreach ( array('validator', 'v') as $v ) {
                    $key    = $fd.':'.$v;
                    $vars[$key] = $val;
                    $Tpl->add(array_merge(array($key), $block), array($key => $val));
                }

                $aryMethod  = $Field->getMethods($fd);
                foreach ( $aryMethod as $method ) {
                    if ( !$val = intval($Field->isValid($fd, $method)) ) {
                        foreach ( array('validator', 'v') as $v ) {
                            $key    = $fd.':'.$v.'#'.$method;
                            $vars[$key] = $val;
                            $Tpl->add(array_merge(array($key), $block), array($key => $val));
                        }

                        $cnt    = count($Field->getArray($fd));
                        for ( $i=0; $i<$cnt; $i++ ) {
                            if ( !$val = intval($Field->isValid($fd, $method, $i)) ) {
                                foreach ( array('validator', 'v') as $v ) {
                                    $key    = $fd.'['.$i.']'.':'.$v.'#'.$method;
                                    $vars[$key] = $val;
                                    $Tpl->add(array_merge(array($key), $block), array($key => $val));
                                }
                            } else {
                                continue;
                            }
                        }
                    } else {
                        continue;
                    }
                }
            } else {
                continue;
            }
        }
        //-------
        // touch
        foreach ( $data as $fd => $vals ) {
            if ( !is_array($vals) ) {
                $vals   = array($vals);
            }
            foreach ( $vals as $i => $val ) {
                if ( empty($i) ) {
                    if ( !is_array($val) ) {
                        $Tpl->add(array_merge(array($fd.':touch#'.$val), $block));
                    }
                }
                if ( !is_array($val) ) {
                    $Tpl->add(array_merge(array($fd.'['.$i.']'.':touch#'.$val), $block));
                }
            }
        }

        $vars   += ACMS_GET::buildInputTextValue($data, $Tpl, $block);
        $vars   += ACMS_GET::buildInputCheckboxChecked($data, $Tpl, $block);
        $vars   += ACMS_GET::buildSelectSelected($data, $Tpl, $block);

        if ( !is_null($scp) ) $vars[(!empty($scp) ? $scp.':' : '').'takeover'] = acmsSerialize($Field);


        foreach ( $Field->listChildren() as $child ) {
            $vars   += ACMS_GET::buildField($Field->getChild($child), $Tpl, $block, $child);
        }

        return $vars;
    }

    function buildInputTextValue($data, & $Tpl, $block=array())
    {
        if ( !is_array($block) ) $block = array($block);
        $vars   = array();
        foreach ( $data as $key => $val ) {
            if ( is_array($val) ) {
                foreach ( $val as $i => $v ) {

                    if ( empty($i) ) {
                        $vars[$key] = $v;
                        if ( !empty($Tpl) ) {
                            $Tpl->add(array_merge(array($key), $block), array($key => $v));
                        }
                    }

                    $sfx    = '['.$i.']';
                    if ( $v !== '' ) { $vars[$key.$sfx]    = $v; }
                    if ( !empty($Tpl) ) {
                        if ( !empty($i) ) {
                            $Tpl->add(array_merge(array('glue', $key.':loop'), $block));
                            $Tpl->add(array_merge(array($key.':glue', $key.':loop'), $block));
                        }
                        $Tpl->add(array_merge(array($key.':loop'), $block)
                            , !empty($v) ? array($key => $v) : array());
                    }
                }
            } else {

                //--------
                // legacy?
                $vars[$key] = $val;

            }
        }
        return $vars;
    }

    function buildInputCheckboxChecked($data, & $Tpl, $block=array())
    {
        if ( !is_array($block) ) $block = array($block);
        $vars   = array();
        foreach ( $data as $key => $vals ) {
            if ( !is_array($vals) ) $vals   = array($vals);
            foreach ( $vals as $i => $val ) {
                if ( !is_array($val) ) {
                    foreach ( array(
                        $key.':checked#'.$val,
                        $key.'['.$i.']'.':checked#'.$val,
                    ) as $name ) {
                        $vars[$name]    = config('attr_checked');
                        if ( !empty($Tpl) ) {
                            $Tpl->add(array_merge(array($name), $block));
                        }
                    }
                }
            }
        }
        return $vars;
    }

    function buildSelectSelected($data, & $Tpl, $block=array())
    {
        if ( !is_array($block) ) $block = array($block);
        $vars   = array();
        foreach ( $data as $key => $vals ) {
            if ( !is_array($vals) ) $vals   = array($vals);
            foreach ( $vals as $i => $val ) {
                if ( !is_array($val) ) {
                    foreach ( array(
                        $key.':selected#'.$val,
                        $key.'['.$i.']'.':selected#'.$val,
                    ) as $name ) {
                        $vars[$name]    = config('attr_selected');
                        if ( !empty($Tpl) ) {
                            $Tpl->add(array_merge(array($name), $block));
                        }
                    }
                }
            }
        }
        return $vars;
    }

    function buildPager($page, $limit, $amount, $delta, $curAttr, & $Tpl, $block=array(), $Q=array())
    {
        $vars   = array();
        $block  = is_array($block) ? $block : array($block);

        $from   = ($page - 1) * $limit;
        $to     = $from + $limit;// - 1;
        if ( $amount < $to ) {
            $to = $amount;
        }
        $vars   += array(
            'itemsAmount'    => $amount,
            'itemsFrom'      => $from + 1,
            'itemsTo'        => $to,
        );
        $lastPage   = ceil($amount/$limit);
        $fromPage   = 1 > ($page - $delta) ? 1 : ($page - $delta);
        $toPage     = $lastPage < ($page + $delta) ? $lastPage : ($page + $delta);

        if ( 1 < $toPage ) {
            for ( $curPage=$fromPage; $curPage<=$toPage; $curPage++ ) {
                $_vars  = array('page' => $curPage);
                if ( $curPage <> $toPage ) {
                    $Tpl->add(array_merge(array('glue', 'page:loop'), $block));
                }
                if ( PAGE == $curPage ) {
                    $_vars['pageCurAttr']    = $curAttr;
                } else {
                    $Tpl->add(array_merge(array('link#front', 'page:loop'), $block), array(
                        'url'   => acmsLink($Q + array(
                            'page'      => $curPage,
                        )),
                    ));
                    $Tpl->add(array_merge(array('link#rear', 'page:loop'), $block));
                }
                $Tpl->add(array_merge(array('page:loop'), $block), $_vars);
            }
        }

        if ( $toPage <> $lastPage ) {
            $vars   += array(
                'lastPageUrl'   => acmsLink($Q + array(
                    'page'      => $lastPage,
                )),
                'lastPage'  => $lastPage,
            );
        }

        if ( 1 < $fromPage ) {
            $vars   += array(
                'firstPageUrl'   => acmsLink($Q + array(
                    'page'      => 1,
                )),
                'firstPage'  => 1,
            );
        }

        if ( 1 < $page ) {
            $Tpl->add(array_merge(array('backLink'), $block), array(
                'url' => acmsLink($Q + array(
                    'page'      => ($page > 2) ? $page - 1 : false,
                )),
                'backNum'   => $limit,
                'backPage'  => ($page > 2) ? $page - 1 : false, 
            ));
        }
        if ( $page <> $lastPage ) {
            $forwardNum = $amount - ($from + $limit);
            if ( $limit < $forwardNum ) $forwardNum = $limit;
            $Tpl->add(array_merge(array('forwardLink'), $block), array(
                'url' => acmsLink($Q + array(
                    'page'      => $page + 1,
                )),
                'forwardNum'    => $forwardNum,
                'forwardPage'   => $page + 1,
            ));
        }

        return $vars;
    }

    function checkShortcut($action, $admin, $idKey, $id)
    {
        $admin  = str_replace('/', '_', $admin);

        $aryAuth    = array();
        if ( sessionWithContribution() ) $aryAuth[] = 'contribution';
        if ( sessionWithCompilation() ) $aryAuth[]  = 'compilation';
        if ( sessionWithAdministration() ) $aryAuth[]   = 'administration';

        $DB     = DB::singleton(dsn());
        $SQL    = SQL::newSelect('dashboard');
        $SQL->setSelect('dashboard_key');
        $SQL->addWhereOpr('dashboard_key', 'shortcut_'.$idKey.'_'.$id.'_'.$admin.'_auth');
        $SQL->addWhereIn('dashboard_value', $aryAuth);
        $SQL->addWhereOpr('dashboard_blog_id', BID);

        return !!$DB->query($SQL->get(dsn()), 'one');
    }
    
    function buildImage(&$Tpl, $pimageId, $config)
    {
        $DB         = DB::singleton(dsn());
        $vars       = array();
        $pathAry    = array();
        
        //-------
        // image
        if ( !empty($pimageId) ) {
            $SQL    = SQL::newSelect('column');
            $SQL->addWhereOpr('column_id', $pimageId);
            $pimage = $DB->query($SQL->get(dsn()), 'row');
            $filename   = $pimage['column_field_2'];
            $align      = $pimage['column_align'];
            $pathAry    = explodeUnitData($filename);
        } else {
            $path   = null;
        }

        if ( empty($pathAry) ) {
            $Tpl->add('noimage', array(
                'noImgX'  => $config['imageX'],
                'noImgY'  => $config['imageY'],
            ));
            
            return array();
        }

        foreach ( $pathAry as $i => $path ) {
            if ($i == 0) $fx = '';
            else $fx = ++$i;

            $path       = ARCHIVES_DIR.$path;
            if ( $align === 'hidden' ) $path = null;

            //-------------------
            // image is readble?
            if ( is_readable($path) ) {
                list($x, $y)    = @getimagesize($path);

                if ( max($config['imageX'], $config['imageY']) > max($x, $y) ) {
                    $_path  = preg_replace('@(.*?)([^/]+)$@', '$1large-$2',  $path);
                    if ( $xy = @getimagesize($_path) ) {
                        $path   = $_path;
                        $x      = $xy[0];
                        $y      = $xy[1];
                    }
                }

                $vars   += array(
                    'path'.$fx  => $path,
                );
                if ( 'on' == $config['imageTrim'] ) {
                    if ( $x > $config['imageX'] and $y > $config['imageY'] ) {
                        if ( ($x / $config['imageX']) < ($y / $config['imageY']) ) {
                            $imgX   = $config['imageX'];
                            $imgY   = @round($y / ($x / $config['imageX']));
                        } else {
                            $imgY   = $config['imageY'];
                            $imgX   = @round($x / ($y / $config['imageY']));
                        }
                    } else {
                        if ( $x < $config['imageX'] ) {
                            $imgX   = $config['imageX'];
                            $imgY   = @round($y * ($config['imageX'] / $x));
                        } else if ( $y < $config['imageY'] ) {
                            $imgY   = $config['imageY'];
                            $imgX   = @round($x * ($config['imageY'] / $y));
                        } else {
                            if ( ($config['imageX'] - $x) > ($config['imageY'] - $y) ) {
                                $imgX   = $config['imageX'];
                                $imgY   = @round($y * ($config['imageX'] / $x));
                            } else {
                                $imgY   = $config['imageY'];
                                $imgX   = @round($x * ($config['imageY'] / $y));
                            }
                        }
                    }
                    $config['imageCenter']  = 'on';
                } else {
                    if ( $x > $config['imageX'] ) {
                        if ( $y > $config['imageY'] ) {
                            if ( ($x - $config['imageX']) < ($y - $config['imageY']) ) {
                                $imgY   = $config['imageY'];
                                $imgX   = @round($x / ($y / $config['imageY']));
                            } else {
                                $imgX   = $config['imageX'];
                                $imgY   = @round($y / ($x / $config['imageX']));
                            }
                        } else {
                            $imgX   = $config['imageX'];
                            $imgY   = round($y / ($x / $config['imageX']));
                        }
                    } else if ( $y > $config['imageY'] ) {
                        $imgY   = $config['imageY'];
                        $imgX   = round($x / ($y / $config['imageY']));
                    } else {
                        if ( 'on' == $config['imageZoom'] ) {
                            if ( ($config['imageX'] - $x) > ($config['imageY'] - $y) ) {
                                $imgY   = $config['imageY'];
                                $imgX   = round($x * ($config['imageY'] / $y));
                            } else {
                                $imgX   = $config['imageX'];
                                $imgY   = round($y * ($config['imageX'] / $x));
                            }
                        } else {
                            $imgX   = $x;
                            $imgY   = $y;
                        }
                    }
                }

                //-------
                // align
                if ( 'on' == $config['imageCenter'] ) {
                    if ( $imgX > $config['imageX'] ) {
                        $left   = round((-1 * ($imgX - $config['imageX'])) / 2);
                    } else {
                        $left   = round(($config['imageX'] - $imgX) / 2);
                    }
                    if ( $imgY > $config['imageY'] ) {
                        $top    = round((-1 * ($imgY - $config['imageY'])) / 2);
                    } else {
                        $top    = round(($config['imageY'] - $imgY) / 2);
                    }
                } else {
                    $left   = 0;
                    $top    = 0;
                }

                $vars   += array(
                    'imgX'.$fx  => $imgX,
                    'imgY'.$fx  => $imgY,
                    'left'.$fx  => $left,
                    'top'.$fx   => $top,
                    'alt'.$fx   => $pimage['column_field_4'],
                );

                //------
                // tiny
                $tiny   = ARCHIVES_DIR.preg_replace('@(.*?)([^/]+)$@', '$1tiny-$2', $filename);
                if ( $xy = @getimagesize($tiny) ) {
                    $vars   += array(
                        'tinyPath'.$fx  => $tiny,
                        'tinyX'.$fx     => $xy[0],
                        'tinyY'.$fx     => $xy[1],
                    );
                }
                
                //--------
                // square
                $square = ARCHIVES_DIR.preg_replace('@(.*?)([^/]+)$@', '$1square-$2', $filename);
                if ( @is_file($square) ) {
                    $vars   += array(
                        'squarePath'.$fx    => $square,
                        'squareX'.$fx       => $this->squareSize,
                        'squareY'.$fx       => $this->squareSize,
                    );
                }

            } else {
                $Tpl->add('noimage', array(
                    'noImgX'  => $config['imageX'],
                    'noImgY'  => $config['imageY'],
                ));
            }
            $vars   += array(
                'x'.$fx => $config['imageX'],
                'y'.$fx => $config['imageY'],
            );
        }
        
        return $vars;
    }

    function buildTag(& $Tpl, $eid)
    {
        $DB     = DB::singleton(dsn());
        $SQL    = SQL::newSelect('tag');
        $SQL->addSelect('tag_name');
        $SQL->addSelect('tag_blog_id');
        $SQL->addWhereOpr('tag_entry_id', $eid);
        $SQL->addOrder('tag_sort');

        $q  = $SQL->get(dsn());

        do {
            if ( !$DB->query($q, 'fetch') ) break;
            if ( !$row = $DB->fetch($q) ) break;
            $stack  = array();
            array_push($stack, $row);
            array_push($stack, $DB->fetch($q));
            while ( $row = array_shift($stack) ) {
                if ( !empty($stack[0]) ) $Tpl->add(array('tagGlue', 'tag:loop'));
                $Tpl->add('tag:loop', array(
                    'name'  => $row['tag_name'],
                    'url'   => acmsLink(array(
                        'bid'   => $row['tag_blog_id'],
                        'tag'   => $row['tag_name'],
                    )),
                ));
                array_push($stack,$DB->fetch($q));
            }
        } while ( false );

        return true;
    }

    function buildRelatedEntries(& $Tpl, $eids=array(), $block=array())
    {
        $block      = !empty($block) ? (is_array($block) ? $block : array($block)) : array();
        $loopblock  = array_merge(array('related:loop'), $block);

        $DB     = DB::singleton(dsn());
        $SQL    = SQL::newSelect('entry');
        $SQL->addWhereIn('entry_id', $eids);
        ACMS_Filter::entrySpan($SQL, $this->start, $this->end);
        ACMS_Filter::entrySession($SQL);
        $SQL->setFieldOrder('entry_id', $eids);
        $all    = $DB->query($SQL->get(dsn()), 'all');

        foreach ( $all as $row ) {
            $bid    = intval($row['entry_blog_id']);
            $cid    = intval($row['entry_category_id']);
            $eid    = intval($row['entry_id']);
            $clid   = $row['entry_primary_image'];
            $vars   = array(
                'related.eid'           => $eid,
                'related.bid'           => $bid,
                'related.cid'           => $cid,
                'related.categoryName'  => ACMS_RAM::categoryName($cid),
                'related.permalink' => acmsLink(array(
                    'bid'   => $bid,
                    'cid'   => $cid,
                    'eid'   => $eid,
                ), false),
            );
            $title  = IS_LICENSED ? $row['entry_title'] : '[test]'.$row['entry_title'];
            $title  = addPrefixEntryTitle($row['entry_title']
                , $row['entry_status']
                , $row['entry_start_datetime']
                , $row['entry_end_datetime']
                , $row['entry_approval']
            );
            $vars['related.title']  = $title;
            $link   = $row['entry_link'];
            $url    = acmsLink(array(
                'bid'   => $bid,
                'cid'   => $cid,
                'eid'   => $eid,
            ));
            if ( $link != '#' ) {
                $vars['related.url']  = !empty($link) ? $link : $url;
            }

            $Tpl->add($loopblock, $vars);
        }
    }

    function buildSummary(&$Tpl, $row, $count, $gluePoint, $config, $extraVars = array())
    {
        $this->squareSize = config('image_size_square');
        if ( $row && isset($row['entry_id']) ) {
            if ( !IS_LICENSED ) $row['entry_title'] = '[test]'.$row['entry_title'];

            $bid    = intval($row['entry_blog_id']);
            $uid    = intval($row['entry_user_id']);
            $cid    = intval($row['entry_category_id']);
            $eid    = intval($row['entry_id']);
            $clid   = intval($row['entry_primary_image']);
            $sort   = intval($row['entry_sort']);
            $csort  = intval($row['entry_category_sort']);
            $usort  = intval($row['entry_user_sort']);

            $ecd    = $row['entry_code'];
            $link   = $row['entry_link'];
            $status = $row['entry_status'];
            $permalink  = acmsLink(array(
                'bid'   => $bid,
                'cid'   => $cid,
                'eid'   => $eid,
            ), false);
            $url        = acmsLink(array(
                'bid'   => $bid,
                'cid'   => $cid,
                'eid'   => $eid,
            ));
            $title  = addPrefixEntryTitle($row['entry_title']
                , $status
                , $row['entry_start_datetime']
                , $row['entry_end_datetime']
                , $row['entry_approval']
            );
            
            if ( $count % 2 == 0 ) {
                $oddOrEven  = 'even';
            } else {
                $oddOrEven  = 'odd';
            }
            
            $vars   = array(
                'permalink'     => $permalink,
                'title'         => $title,
                'eid'           => $eid,
                'ecd'           => $ecd,
                'uid'           => $uid,
                'bid'           => $bid,
                'sort'          => $sort,
                'csort'         => $csort,
                'usort'         => $usort,
                'iNum'          => $count,
                'sNum'          => (($this->page - 1) * $config['limit']) + $count,
                'oddOrEven'     => $oddOrEven,
                'status'        => $status,
                'entry:loop.class' => isset($config['loop_class']) ? $config['loop_class'] : '',
            );
            
            if ( $link != '#' ) {
                $vars += array(
                    'url' => !empty($link) ? $link : $url,
                );
                $Tpl->add('url#rear');
            }

            if ( !isset($config['blogInfoOn']) or $config['blogInfoOn'] === 'on' ) {
                $blogName   = ACMS_RAM::blogName($bid);
                $blogCode   = ACMS_RAM::blogCode($bid);
                $blogUrl    = acmsLink(array(
                    'bid'   => $bid,
                ));
                $vars   += array(
                    'blogName'  => $blogName,
                    'blogCode'  => $blogCode,
                    'blogUrl'   => $blogUrl,
                );
            }

            if ( !empty($cid) and (!isset($config['categoryInfoOn']) or $config['categoryInfoOn'] === 'on')) {
                $categoryName   = ACMS_RAM::categoryName($cid);
                $categoryCode   = ACMS_RAM::categoryCode($cid);
                $categoryUrl    = acmsLink(array(
                    'bid'   => $bid,
                    'cid'   => $cid,
                ));
                
                $vars   += array(
                    'categoryName'  => $categoryName,
                    'categoryCode'  => $categoryCode,
                    'categoryUrl'   => $categoryUrl,
                    'cid'           => $cid,
                );
            }

            //----------------------
            // attachment vars
            foreach ( $extraVars as $key => $val ) {
                if ( !empty($row[$val]) ) {
                    $vars   += array($key => $row[$val]);
                }
            }

            //-----
            // new
            if ( requestTime() <= strtotime($row['entry_datetime']) + $config['newtime'] ) {
                $Tpl->add('new');
            }

            //-----
            //image
            if(!isset($config['mainImageOn']) or $config['mainImageOn'] === 'on') {
                $vars   += $this->buildImage($Tpl, $clid, $config);
            }

            //----------
            // fulltext
            if(!isset($config['fullTextOn']) or $config['fullTextOn'] === 'on'){
                $this->buildSummaryFulltext($vars, $eid); 
                if ( 1
                    && isset($vars['summary'])
                    && isset($config['fulltextWidth'])
                    && !empty($config['fulltextWidth'])
                ) {
                    $width  = intval($config['fulltextWidth']);
                    $marker = isset($config['fulltextMarker']) ? $config['fulltextMarker'] : '';
                    $vars['summary']    = mb_strimwidth($vars['summary'], 0, $width, $marker, 'UTF-8');
                }
            }

            //------
            // date
            $vars   += $this->buildDate($row['entry_datetime'], $Tpl, 'entry:loop');
            if ( !isset($config['detailDateOn']) or $config['detailDateOn'] === 'on' ) {
                $vars   += $this->buildDate($row['entry_updated_datetime'], $Tpl, 'entry:loop', 'udate#');
                $vars   += $this->buildDate($row['entry_posted_datetime'], $Tpl, 'entry:loop', 'pdate#');
            }

            //-------------
            // entry field
            if ( !isset($config['entryFieldOn']) or $config['entryFieldOn'] === 'on' ) {
                $vars   += $this->buildField(loadEntryField($eid), $Tpl);
            }
            
            //-------------
            // user field
            if ( isset($config['userInfoOn']) and $config['userInfoOn'] === 'on' ) {
                if ( $config['userFieldOn'] === 'on' ) {
                    $Field  = loadUserField($uid);
                } else {
                    $Field  = new Field();
                }
                $Field->setField('fieldUserName', ACMS_RAM::userName($uid));
                $Field->setField('fieldUserCode', ACMS_RAM::userCode($uid));
                $Field->setField('fieldUserStatus', ACMS_RAM::userStatus($uid));
                $Field->setField('fieldUserMail', ACMS_RAM::userMail($uid));
                $Field->setField('fieldUserMailMobile', ACMS_RAM::userMailMobile($uid));
                $Field->setField('fieldUserUrl', ACMS_RAM::userUrl($uid));
                $Field->setField('fieldUserIcon', loadUserIcon($uid));
                if ( $large = loadUserLargeIcon($uid) ) {
                    $Field->setField('fieldUserLargeIcon', $large);
                }
                $Tpl->add('userField', $this->buildField($Field, $Tpl));
            }
            
            //------------
            // blog field
            if ( isset($config['blogInfoOn']) and $config['blogInfoOn'] === 'on' ) {
                if ( $config['blogFieldOn'] === 'on' ) {
                    $Field  = loadBlogField($bid);
                } else {
                    $Field  = new Field();
                }
                $Field->setField('fieldBlogName', $blogName);
                $Field->setField('fieldBlogCode', $blogCode);
                $Field->setField('fieldBlogUrl', $blogUrl);
                $Tpl->add('blogField', $this->buildField($Field, $Tpl));
            }
            
            //----------------
            // category field
            if( !empty($cid) and isset($config['categoryInfoOn']) and $config['categoryInfoOn'] === 'on' ){
                if( $config['categoryFieldOn'] === 'on') {
                    $Field  = loadCategoryField($cid);
                } else {
                    $Field  = new Field();
                }
                $Field->setField('fieldCategoryName', $categoryName);
                $Field->setField('fieldCategoryCode', $categoryCode);
                $Field->setField('fieldCategoryUrl', $categoryUrl);
                $Field->setField('fieldCategoryId', $cid);
                $Tpl->add('categoryField', $this->buildField($Field, $Tpl));
            }

            //-----
            // tag
            if ( isset($config['tagOn']) && $config['tagOn'] === 'on' ) {
                $this->buildTag($Tpl, $eid);
            }
            
            //------
            // glue
            $addend = ($count === $gluePoint);
            if ( !$addend ) {
                $Tpl->add(array_merge(array('glue', 'entry:loop')));
            }
            $Tpl->add('entry:loop', $vars);
            
            if ( $addend ) {
                $Tpl->add('unit:loop');
            } else if ( $count != 0 && $config['unit'] > 0 ) {
                if ( !($count % $config['unit']) ) {
                    $Tpl->add('unit:loop');
                }
            }
        }
    }

    function buildSummaryFulltext(& $vars, $eid)
    {
        $DB     = DB::singleton(dsn());
        $SQL    = SQL::newSelect('column');
        $SQL->addWhereOpr('column_entry_id', $eid);
        $q      = $SQL->get(dsn());

        $textData  = array();
        if ( $DB->query($q, 'fetch') and ($row = $DB->fetch($q)) ) { do {
            if ( $row['column_align'] === 'hidden' ) continue;
            $type   = $row['column_type'];
            if ( 'text' == $type ) {
                $_text  = $row['column_field_1'];
                switch( $row['column_field_2'] ){
                    case 'markdown':
                        require_once LIB_DIR.'Markdown.php';
                        $_text = Markdown($_text);
                        break;
                    case 'table':
                        $_text = ACMS_Corrector::table($_text);
                        break;
                }
                $text   = preg_replace('@\s+@', ' ', strip_tags($_text));
                $data   = explodeUnitData($text);
                foreach ( $data as $i => $txt ) {
                    if ( isset($textData[$i]) ) {
                        $textData[$i] .= $txt.' ';
                    } else {
                        $textData[] = $txt.' ';
                    }
                }
            }
        } while ( $row = $DB->fetch($q) ); }
        buildUnitData($textData, $vars, 'summary');
    }
}

<?php

use Yaf\Application;
use Yaf\Dispatcher;
class IssueController extends BaseGetController
{
    public $db;

    public function init()
    {
        parent::init();
    }

    public function listAction(){
        $list = $this->db->field('id,problem,result')->table('ny_issue')
            ->limit(0,10)
            ->select();
        $this->status($list);
    }
}

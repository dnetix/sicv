<?php  namespace SICV\Utils\LineageModel;

class LineageNode {

    public $id;
    public $sw = 0;
    public $data;
    public $parentIds = [];

    public $back;
    public $next;

    public function __construct(){

    }

    public function getData(){
        return $this->data;
    }

    public function getNext(){
        return $this->next;
    }

    public function getLevel(){
        return count($this->parentIds);
    }

    public function getLineageIds(){
        $lineageIds = $this->parentIds;
        $lineageIds[] = $this->id;
        return $lineageIds;
    }

    public function isList(){
        return $this->sw == 1 ? true : false;
    }

}
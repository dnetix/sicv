<?php  namespace SICV\Utils\LineageModel;

class LineageTree {

    public $root;
    protected $index;
    public $lastNode;

    protected $stack = [];

    public function __construct(){
        $this->root = new LineageNode();
        $this->root->sw = 0;
        $this->root->data = 'root';
        $this->index =& $this->root;
        $this->lastNode =& $this->root;
    }

    public function addNode($id, $parentId, $data){
        $this->reset();

        $parentIds = [];

        if(!is_null($parentId)){
            $lastNode = $this->root;
            $node = $this->nextNode();
            while(!is_null($node) && $node->id != $parentId){
                $lastNode = $node;
                $node = $this->nextNode();
            }

            if(is_null($node)){
                throw new Exception("Parent ID: {$parentId} not found", 1);
            }

            $lastStack = null;
            if(count($this->stack) > 0){
                while($firstStack = array_shift($this->stack)){
                    $lastStack = $firstStack;

                    $parentIds[] = $firstStack->data->id;
                }
            }

            if(is_null($lastStack) || $lastStack->data != $node){
                $newNode = new LineageNode();
                $newNode->sw = 1;
                $newNode->data = $node;
                $newNode->next = $node->next;

                $lastNode->next = $newNode;
                $node->next = null;

                $parentIds[] = $parentId;
            }else{
                $node = $this->getLastLevelNode($node);
            }

        }else{
            $node = $this->getLastLevelNode();
        }

        $newNode = new LineageNode();
        $newNode->id = $id;
        $newNode->data = $data;
        $newNode->parentIds = $parentIds;

        $node->next = $newNode;

        $this->reset();
    }

    public function getLevel(){
        return count($this->stack);
    }

    public function nextNode(){
        $next = $this->index;

        if($next->sw == 1){
            array_push($this->stack, $next);
            $next = $next->data;
        }else{
            if(is_null($next->next)){

                while(is_null($next->next) && count($this->stack) > 0){
                    $next = array_pop($this->stack);
                }
                $next = $next->next;

            }else{
                $next = $next->next;
                if($next->sw == 1){
                    array_push($this->stack, $next);
                    $next = $next->data;
                }
            }
        }

        $this->index =& $next;
        if(is_null($next)){
            $this->reset();
        }
        return $next;
    }

    public function getLastLevelNode($lastNode = null){
        if(is_null($lastNode)){
            $lastNode = $this->root;
        }
        while (!is_null($lastNode->next)) {
            $lastNode = $lastNode->next;
        }
        return $lastNode;
    }

    public function reset(){
        $this->index =& $this->root;
        $this->stack = [];
    }

}
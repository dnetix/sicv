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

    public function printAsHTMLSelect($selectName, $blankOption = null, $selectedId = null){
        $select[] = "<select name=\"{$selectName}\" class=\"form-control\">";
        if(!is_null($blankOption)){
            $select[] = "<option value=\"\">{$blankOption}</option>";
        }
        while($node = $this->nextNode()){
            $selectedTag = ($node->id == $selectedId) ? ' selected="selected"' : '';
            $optionContent = is_object($node->getData()) ? $node->getData()->getName() : $node->getData();
            $select[] = "<option value=\"{$node->id}\"{$selectedTag}>{$optionContent}</option>";
        }
        $select[] = "</select>";
        return implode("\n", $select);
    }

    public function printAsHTMLSelectWithStructure($selectName, $blankOption = null, $selectedId = null, $selectIdTag = null){
        $select[] = "<select name=\"{$selectName}\" class=\"form-control article_type\" id=\"{$selectIdTag}\">";
        if(!is_null($blankOption)){
            $select[] = "<option value=\"\">{$blankOption}</option>";
        }
        $opengroup = false;
        while($node = $this->nextNode()){
            $nodeLevel = $node->getLevel();
            $optionContent = is_object($node->getData()) ? $node->getData()->toString() : $node->getData();
            if($nodeLevel == 0){
                if($opengroup){
                    $opengroup = false;
                    $select[] = "</optgroup>";
                    $select[] = "<optgroup label=\"{$optionContent}\">";
                }else{
                    $opengroup = true;
                    $select[] = "<optgroup label=\"{$optionContent}\">";
                }
            }else{
                $selectedTag = ($node->id == $selectedId) ? ' selected="selected"' : '';
                $select[] = "<option value=\"{$node->id}\"{$selectedTag}>{$optionContent}</option>";
            }
        }
        if($opengroup){
            $select[] = "</optgroup>";
        }
        $select[] = "</select>";
        return implode("\n", $select);
    }

}
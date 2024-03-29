<?php

namespace App\Models\Utils\Hierachical;

use Exception;

class HierarchicalTree implements \Iterator
{
    /**
     * @var HierarchicalNode
     */
    public $root;
    /**
     * @var HierarchicalNode
     */
    public $index;
    /**
     * @var HierarchicalNode
     */
    public $lastNode;

    protected $stack = [];
    public $keys = [];

    public $limitedTo = null;

    public function __construct()
    {
        $this->root = new HierarchicalNode(0, 'root');
        $this->index = $this->root;
        $this->lastNode = $this->root;
    }

    public function addNode($key, $data, $parentKey)
    {
        $this->reset();
        $parentKeys = [];

        if (empty($key)) {
            throw new Exception('You must define a key for the node');
        }

        if (!is_null($parentKey)) {
            $this->lastNode = $this->index();
            $node = $this->nextNode();
            while (!is_null($node) && $node->key() != $parentKey) {
                $this->lastNode = $node;
                $node = $this->nextNode();
            }

            if (is_null($node)) {
                throw new Exception("ParentKey: {$parentKey} not found", 1);
            }

            $lastStack = null;
            if ($this->countStack() > 0) {
                while ($firstStack = $this->shiftStack()) {
                    $lastStack = $firstStack;
                    $parentKeys[] = $firstStack->data()->key();
                }
            }

            if (is_null($lastStack) || $lastStack->data() != $node) {
                $listNode = (new HierarchicalNode(
                    null,
                    $node
                ))->setNext($node->next());
                $this->lastNode->setNext($listNode);
                $parentKeys[] = $parentKey;
            } else {
                $node = $this->getLastSameLevelNode($node);
            }
        } else {
            $node = $this->getLastSameLevelNode();
        }

        $newNode = (new HierarchicalNode(
            $key,
            $data,
            $parentKeys
        ));
        $node->setNext($newNode);

        $this->keys[] = $key;

        $this->reset();
    }

    public function nextNode()
    {
        $node = $this->index();

        if (is_null($node->next())) {
            while (is_null($node->next()) && $this->countStack() > 0) {
                $node = $this->popStack();
                $this->lastNode = $node;
            }
            $node = $node->next();
        } else {
            $node = $node->next();
        }

        if (!is_null($node) && $node->isList()) {
            $this->pushStack($node);
            $node = $node->data();
        }

        $this->index = $node;
        return $node;
    }

    public function index()
    {
        return $this->index;
    }

    protected function popStack()
    {
        return array_pop($this->stack);
    }

    protected function shiftStack()
    {
        return array_shift($this->stack);
    }

    protected function pushStack($node)
    {
        array_push($this->stack, $node);
    }

    protected function countStack()
    {
        return count($this->stack);
    }

    /**
     * Obtains the last node from the same level, to add as last one.
     * @param null $lastNode
     * @return HierarchicalNode
     */
    public function getLastSameLevelNode($lastNode = null)
    {
        if (is_null($lastNode)) {
            $lastNode = $this->root;
        }
        while (!is_null($lastNode->next())) {
            $lastNode = $lastNode->next();
        }
        return $lastNode;
    }

    public function reset($respectLimited = false)
    {
        if ($respectLimited && !is_null($this->limitedTo)) {
            $this->index = $this->limitedTo;
        } else {
            $this->index = $this->root;
        }
        $this->lastNode = null;
        $this->stack = [];
    }

    public function limitListTo($node)
    {
        if (!($node instanceof HierarchicalNode)) {
            $node = $this->findNodeByKey($node);
        }
        $this->reset();
        $this->limitedTo = $node;
        return $this;
    }

    public function noLimit()
    {
        $this->limitedTo = null;
        return $this;
    }

    public function findNodeByKey($key)
    {
        $this->reset();
        if (!in_array($key, $this->keys)) {
            return null;
        }
        while ($node = $this->nextNode()) {
            if ($node->key() == $key) {
                return $node;
            }
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element.
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        if ($this->index() == $this->root) {
            return $this->nextNode();
        }
        return $this->index();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element.
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->nextNode();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element.
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if ($this->index() == $this->root) {
            $node = $this->nextNode();
            if (is_null($node)) {
                return null;
            }
            return $node->key();
        } elseif (is_null($this->index())) {
            return null;
        }
        return $this->index()->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid.
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return is_null($this->key()) ? false : true;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element.
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->reset(true);
    }
}

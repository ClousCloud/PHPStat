<?php

namespace nurazlib\phpstat\rules;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class DeadCodeRule extends NodeVisitorAbstract
{
    private $calledFunctions = [];
    private $declaredFunctions = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall) {
            $this->calledFunctions[] = $node->name->toString();
        }

        if ($node instanceof Node\Stmt\Function_) {
            $this->declaredFunctions[] = $node->name->name;
        }
    }

    public function getDeadFunctions()
    {
        return array_diff($this->declaredFunctions, $this->calledFunctions);
    }
}

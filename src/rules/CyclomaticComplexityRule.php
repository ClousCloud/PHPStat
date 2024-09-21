<?php

namespace nurazlib\phpstat\rules;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class CyclomaticComplexityRule extends NodeVisitorAbstract
{
    private $complexity = 1;

    public function enterNode(Node $node)
    {
        // Tambah kompleksitas untuk struktur kontrol
        if ($node instanceof Node\Stmt\If_ || 
            $node instanceof Node\Stmt\For_ || 
            $node instanceof Node\Stmt\Foreach_ || 
            $node instanceof Node\Stmt\While_ || 
            $node instanceof Node\Stmt\Switch_ || 
            $node instanceof Node\Expr\Ternary) {
            $this->complexity++;
        }
    }

    public function getComplexity()
    {
        return $this->complexity;
    }
}

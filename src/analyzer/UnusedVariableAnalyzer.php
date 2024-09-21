<?php

namespace nurazlib\phpstat\analyzer;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class UnusedVariableAnalyzer extends NodeVisitorAbstract
{
    private $declaredVariables = [];
    private $usedVariables = [];

    public function enterNode(Node $node)
    {
        // Catat variabel yang dideklarasikan
        if ($node instanceof Node\Expr\Assign && $node->var instanceof Node\Expr\Variable) {
            $this->declaredVariables[] = $node->var->name;
        }

        // Catat variabel yang digunakan
        if ($node instanceof Node\Expr\Variable) {
            $this->usedVariables[] = $node->name;
        }
    }

    public function getUnusedVariables()
    {
        return array_diff($this->declaredVariables, $this->usedVariables);
    }
}

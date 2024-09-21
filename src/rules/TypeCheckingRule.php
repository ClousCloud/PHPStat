<?php

namespace Nurazlib\PHPStat\Rules;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TypeCheckingRule extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Function_) {
            // Periksa return type function
            if ($node->getReturnType()) {
                echo "Function " . $node->name->name . " has a return type of " . $node->getReturnType() . "\n";
            } else {
                echo "Warning: Function " . $node->name->name . " has no return type specified.\n";
            }

            // Periksa tipe data parameter
            foreach ($node->params as $param) {
                if ($param->type) {
                    echo "Parameter " . $param->var->name . " has type " . $param->type . "\n";
                } else {
                    echo "Warning: Parameter " . $param->var->name . " has no type specified.\n";
                }
            }
        }
    }
}

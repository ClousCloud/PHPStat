<?php
namespace nurazlib\phpstat\rules;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class DeadCodeRule extends NodeVisitorAbstract
{
    private $variableUsages = [];
    private $declaredVariables = [];

    // When entering a node (like a function, method, or other block)
    public function enterNode(Node $node)
    {
        // If the node is a variable declaration
        if ($node instanceof Node\Expr\Assign && $node->var instanceof Node\Expr\Variable) {
            $variableName = $node->var->name;

            // Ensure the declared variable is a string (valid variable name)
            if (is_string($variableName)) {
                $this->declaredVariables[$variableName] = $node->getLine();
                echo "Variable declared: $" . $variableName . " at line " . $node->getLine() . "\n";
            }
        }

        // If the variable is used elsewhere in the code
        if ($node instanceof Node\Expr\Variable) {
            $variableName = $node->name;

            // Ensure the used variable is a string (valid variable name)
            if (is_string($variableName)) {
                $this->variableUsages[$variableName][] = $node->getLine();
                echo "Variable used: $" . $variableName . " at line " . $node->getLine() . "\n";
            }
        }
    }

    // When traversal is finished, we can check for unused variables
    public function afterTraverse(array $nodes)
    {
        foreach ($this->declaredVariables as $variable => $declarationLine) {
            if (!isset($this->variableUsages[$variable])) {
                // Variable declared but never used
                echo "Warning: Variable $" . $variable . " declared at line " . $declarationLine . " is never used.\n";
            }
        }
    }
}

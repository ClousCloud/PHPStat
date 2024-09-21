<?php

namespace nurazlib\phpstat\rules;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class DeadCodeRule extends NodeVisitorAbstract
{
    private $variableUsages = [];
    private $declaredVariables = [];

    // Ketika memasuki sebuah node (seperti fungsi, metode, atau blok lainnya)
    public function enterNode(Node $node)
    {
        // Jika node adalah deklarasi variabel
        if ($node instanceof Node\Expr\Assign && $node->var instanceof Node\Expr\Variable) {
            $variableName = $node->var->name;

            // Pastikan variabel yang dideklarasikan adalah string (nama variabel yang valid)
            if (is_string($variableName)) {
                $this->declaredVariables[$variableName] = $node->getLine();
                echo "Variable declared: $" . $variableName . " at line " . $node->getLine() . "\n";
            }
        }

        // Jika variabel digunakan di tempat lain dalam kode
        if ($node instanceof Node\Expr\Variable) {
            $variableName = $node->name;

            // Pastikan variabel yang digunakan adalah string (nama variabel yang valid)
            if (is_string($variableName)) {
                $this->variableUsages[$variableName][] = $node->getLine();
                echo "Variable used: $" . $variableName . " at line " . $node->getLine() . "\n";
            }
        }
    }

    // Ketika traversal selesai, kita bisa memeriksa variabel yang tidak digunakan
    public function afterTraverse(array $nodes)
    {
        foreach ($this->declaredVariables as $variable => $declarationLine) {
            if (!isset($this->variableUsages[$variable])) {
                // Variabel yang dideklarasikan tapi tidak digunakan
                echo "Warning: Variable $" . $variable . " declared at line " . $declarationLine . " is never used.\n";
            }
        }
    }
}

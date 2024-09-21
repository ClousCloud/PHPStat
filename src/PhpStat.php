<?php

namespace nurazlib\phpstat;

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use nurazlib\phpstat\Rules\TypeCheckingRule;
use nurazlib\phpstat\Rules\CyclomaticComplexityRule;
use nurazlib\phpstat\Rules\DeadCodeRule;

class PhpStat
{
    private $parser;
    private $traverser;

    public function __construct()
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
    }

    public function analyze(string $code)
    {
        try {
            $stmts = $this->parser->parse($code);

            // Tambahkan rules
            $this->traverser->addVisitor(new TypeCheckingRule());
            $complexityRule = new CyclomaticComplexityRule();
            $this->traverser->addVisitor($complexityRule);
            $deadCodeRule = new DeadCodeRule();
            $this->traverser->addVisitor($deadCodeRule);

            // Lakukan traversal AST
            $this->traverser->traverse($stmts);

            // Laporan
            echo "Cyclomatic Complexity: " . $complexityRule->getComplexity() . "\n";
            $deadFunctions = $deadCodeRule->getDeadFunctions();
            if (count($deadFunctions) > 0) {
                echo "Dead functions: " . implode(", ", $deadFunctions) . "\n";
            }

        } catch (\PhpParser\Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }
}

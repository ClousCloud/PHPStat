<?php

namespace nurazlib\phpstat\analyzer;

use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use nurazlib\phpstat\Rules\TypeCheckingRule;
use nurazlib\phpstat\Rules\CyclomaticComplexityRule;
use nurazlib\phpstat\Rules\DeadCodeRule;

class PhpStatAnalyzer
{
    private $parser;
    private $traverser;

    public function __construct()
    {
        // Parser untuk mendapatkan AST dari kode PHP
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = new NodeTraverser();
    }

    public function analyze(string $code)
    {
        try {
            $stmts = $this->parser->parse($code);

            // Tambahkan rules yang akan di-check
            $this->traverser->addVisitor(new TypeCheckingRule());
            $complexityRule = new CyclomaticComplexityRule();
            $this->traverser->addVisitor($complexityRule);
            $deadCodeRule = new DeadCodeRule();
            $this->traverser->addVisitor($deadCodeRule);

            // Lakukan traversal AST untuk setiap rule
            $this->traverser->traverse($stmts);

            // Laporan hasil analisis
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

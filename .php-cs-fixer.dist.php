<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/app/src')
    ->name('*.php')
    ->exclude('var')
    ->exclude('vendor');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_order' => true,
        'single_quote' => true,
        'blank_line_after_namespace' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one']]
    ])
    ->setFinder($finder);

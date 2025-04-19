<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/app/src')
    ->in(__DIR__ . '/tests')
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
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'single_import_per_statement' => true,
        'no_blank_lines_after_class_opening' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_trailing_comma_in_singleline_array' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'binary_operator_spaces' => ['operators' => ['=>' => 'align_single_space_minimal']],
        'no_empty_phpdoc' => true,
        'phpdoc_trim' => true,
        'phpdoc_scalar' => true,
        'phpdoc_align' => true,
        'phpdoc_separation' => true,
    ])
    ->setFinder($finder);

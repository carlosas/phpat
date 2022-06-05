<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(dirname(__DIR__) . '/src')
    ->in(dirname(__DIR__) . '/tests');

$rules = [
    '@PSR12'          => true,
    '@PHP71Migration' => true,
    '@PHP71Migration:risky'      => true,
    '@PHP73Migration'            => true,
    '@PHP74Migration'            => true,
    '@PHP74Migration:risky'      => true,
    //'@PHP80Migration'            => true,
    'array_syntax'                => ['syntax' => 'short'],
    'binary_operator_spaces'      => ['default' => 'align_single_space_minimal'],
    'align_multiline_comment'     => ['comment_type' => 'phpdocs_like'],
    'combine_consecutive_issets'  => true,
    'combine_consecutive_unsets'  => true,
    'compact_nullable_typehint'   => true,
    'escape_implicit_backslashes' => true,
    'explicit_indirect_variable'  => true,
    'explicit_string_variable'    => true,
    'final_internal_class'        => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax'                 => ['syntax' => 'short'],
    //'mb_str_functions'            => true,
    'method_argument_space' => [
        'keep_multiple_spaces_after_comma' => false,
        'on_multiline'                     => 'ensure_fully_multiline',
        'after_heredoc'                    => true,
    ],
    'method_chaining_indentation' => true,
    'no_php4_constructor'         => true,
    'no_superfluous_elseif'       => true,
    'no_useless_else'             => true,
    'no_useless_return'           => true,
    'ordered_class_elements'      => true,
    'no_unused_imports'           => true,
    'ordered_imports'             => true,
    'single_line_after_imports'   => true,
    'global_namespace_import'     => true,
    'php_unit_strict'             => true,
    'phpdoc_order'                => true,
    'phpdoc_types_order'          => true,
    'simplified_null_return'      => true,
    'strict_comparison'           => true,
    'strict_param'                => true,
];

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true);

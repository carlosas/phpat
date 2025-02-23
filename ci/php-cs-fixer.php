<?php declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([
        dirname(__DIR__) . '/src',
        dirname(__DIR__) . '/tests/architecture',
        dirname(__DIR__) . '/tests/unit',
    ]);

$rules = [
    '@PER-CS' => true,
    '@PhpCsFixer' => true,
    PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer::name() => true,
    PhpCsFixerCustomFixers\Fixer\PhpdocArrayStyleFixer::name() => true,
    PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer::name() => true,
    'phpdoc_line_span' => ['property' => 'single', 'const' => 'single'],
    'phpdoc_separation' => ['groups' => [['deprecated', 'link', 'see', 'since'], ['template', 'template-covariant'], ['param', 'return', 'throws']], 'skip_unlisted_annotations' => true],
    'mb_str_functions' => false,
    'compact_nullable_type_declaration' => true,
    'single_line_after_imports' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'blank_line_before_statement' => ['statements' => ['continue', 'declare', 'exit', 'include', 'include_once', 'phpdoc', 'require', 'require_once', 'return', 'switch', 'throw', 'try', 'yield', 'yield_from']],
    'yoda_style' =>  ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    //enable when min php >= 8.0
    'get_class_to_class_keyword' => false,
];

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true);

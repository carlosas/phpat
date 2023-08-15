<?php declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in([dirname(__DIR__) . '/src', dirname(__DIR__) . '/tests'])
    ->exclude('unit/fixtures');

$rules = [
    '@PER'                        => true,
    '@PHP71Migration'             => true,
    '@PHP71Migration:risky'       => true,
    '@PHP73Migration'             => true,
    '@PHP74Migration'             => true,
    '@PHP74Migration:risky'       => true,
    '@PHP80Migration'             => true,
    '@PHP80Migration:risky'       => true,
    '@PHP81Migration'             => true,
    '@PhpCsFixer'                 => true,
    PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer::name() => true,
    PhpCsFixerCustomFixers\Fixer\PhpdocArrayStyleFixer::name() => true,
    PhpCsFixerCustomFixers\Fixer\ConstructorEmptyBracesFixer::name() => true,
    'mb_str_functions'            => true,
    'compact_nullable_typehint'   => true,
    'no_php4_constructor'         => true,
    'single_line_after_imports'   => true,
    'strict_comparison'           => true,
    'strict_param'                => true,
    'yoda_style'                  =>  ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    //enable when min php >= 8.0
    'get_class_to_class_keyword'  => false,
];

return (new PhpCsFixer\Config())
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true);

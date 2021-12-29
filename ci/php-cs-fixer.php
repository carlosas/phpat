<?php

$finder = PhpCsFixer\Finder::create()
    ->in(realpath(__DIR__ . '/..'))
    ->exclude('tests/functional');

$rules = [
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short']
];

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRules($rules);

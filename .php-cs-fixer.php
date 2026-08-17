<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one', 'trait_import' => 'none', 'case' => 'none']],
        'nullable_type_declaration_for_default_null_value' => ['use_nullable_type_declaration' => true],
    ])
    ->setFinder(
        (new PhpCsFixer\Finder())
            ->in(__DIR__)
            ->exclude('vendor')
            ->exclude('tests/Fixture/var')
    )
;

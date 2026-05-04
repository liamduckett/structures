<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@auto' => true,
        '@PhpCsFixer' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'return_assignment' => [
            'skip_named_var_tags' => true,
        ],
    ])
    ->setFinder(
        (new Finder())
            ->in(__DIR__)
    )
;

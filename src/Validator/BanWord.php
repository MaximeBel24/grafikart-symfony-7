<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{
    public function __construct(
        public string $message = '"{{ banWord }}" est un mot bannit',
        public array $banWords = ['spam', 'merde', 'viagra', 'bite', 'pute'],
        ?array $groups = null,
        mixed $payload = null
        )
        {
            parent::__construct(null, $groups, $payload);
        }    
}

<?php

namespace Schuppo\PasswordStrength;

use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class PasswordStrengthTranslationProvider
{
    public function get(Factory $validator): Translator
    {
        /** @var Translator $translator */
        $translator = $validator->getTranslator();
        $translator->addNamespace('password-strength', __DIR__ . '/lang');
        $translator->load('password-strength', 'validation', $translator->locale());

        return $translator;
    }
}

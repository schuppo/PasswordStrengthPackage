<?php

namespace Schuppo\PasswordStrength;

use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

class PasswordStrengthTranslationProvider
{
    /**
     * @param Factory $validator
     * @return Translator
     */
    public function get(Factory $validator)
    {
        /** @var Translator $translator */
        $translator = $validator->getTranslator();
        $translator->addNamespace('password-strength', __DIR__ . '/../lang');
        $translator->load('password-strength', 'validation', $translator->locale());

        return $translator;
    }
}

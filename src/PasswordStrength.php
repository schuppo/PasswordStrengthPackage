<?php

namespace Schuppo\PasswordStrength;

class PasswordStrength
{
    public function validateLetters($value)
    {
        return preg_match('/\pL/', $value);
    }

    public function validateNumbers($value)
    {
        return preg_match('/\pN/', $value);
    }

    public function validateCaseDiff($value)
    {
        return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value);
    }

    public function validateSymbols($value)
    {
        return preg_match('/\p{Z}|\p{S}|\p{P}/', $value);
    }
}

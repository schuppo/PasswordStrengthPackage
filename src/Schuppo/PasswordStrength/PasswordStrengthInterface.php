<?php namespace Schuppo\PasswordStrength;

interface PasswordStrengthInterface {
    public function validateLetters($attribute, $value, $parameters);
    public function validateNumbers($attribute, $value, $parameters);
    public function validateCaseDiff($attribute, $value, $parameters);
    // public function validateEqualsDbEntry($attribute, $value, $parameters);
}

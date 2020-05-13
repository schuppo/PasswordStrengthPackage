<?php

namespace Schuppo\PasswordStrength\Tests\Integration;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Schuppo\PasswordStrength\PasswordStrength;
use Schuppo\PasswordStrength\Tests\TestCase;

class PasswordStrengthTest extends TestCase
{
    private $validation;

    public function __construct()
    {
        parent::__construct();

        $this->validation = new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    public function setUp(): void
    {
        parent::setUp();

        $pS = new PasswordStrength();
        $this->validation->extend('symbols', static function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateSymbols($value);
        });
        $this->validation->extend('case_diff', static function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateCaseDiff($value);
        });
        $this->validation->extend('numbers', static function ($attribute, $value, $parameters) use ($pS) {
            return $pS->validateNumbers($value);
        });
        $this->validation->extend('letters', static function($attribute, $value, $parameters) use ($pS) {
            return $pS->validateLetters($value);
        });
    }

    public function test_symbols_fails_no_symbol(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'tt' ],
            [ 'password' => 'symbols' ]
        );
        $this->assertFalse($validation->passes());
    }

    public function test_symbols_succeeds_with_symbol(): void
    {
    	$symbols = [
            '!', '@', '#', '$', '%',
            '^', '&', '*', '?', '(',
            ')', '-', '_, ', '=', '+',
            '{', '}', ';', ':', ',',
            '<', '.', '>', '\\', '/',
            ' ', "\t"
		];

        foreach($symbols as $symbol) {
            $validation = $this->validation->make(
                [ 'password' => $symbol ],
                [ 'password' => 'symbols' ]
            );
            $this->assertTrue($validation->passes());
        }
    }

    public function test_case_diff_fails_just_lowercase(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'tt' ],
            [ 'password' => 'case_diff' ]
        );
        $this->assertFalse($validation->passes());
    }

    public function test_case_diff_fails_just_uppercase(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'TT' ],
            [ 'password' => 'case_diff' ]
        );
        $this->assertFalse($validation->passes());
    }

    /** @test */
    public function it_handles_cyrillic_letters(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'Ѐѐ' ],
            [ 'password' => 'case_diff' ]
        );
        $this->assertTrue($validation->passes());
    }

    public function test_case_diff_succeeds(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'Tt' ],
            [ 'password' => 'case_diff']
        );
        $this->assertTrue($validation->passes());
    }

    public function test_numbers_fails(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'T' ],
            [ 'password' => 'numbers' ]
        );
        $this->assertFalse($validation->passes());
    }

    public function test_numbers_succeeds(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 1 ],
            [ 'password' => 'numbers' ]
        );
        $this->assertTrue($validation->passes());
    }

    public function test_numbers_succeeds_float(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 1.1 ],
            [ 'password' => 'numbers' ]
        );
        $this->assertTrue($validation->passes());
    }

    public function test_letters_fails(): void
    {
        $validation = $this->validation->make(
            [ 'password' => '1' ],
            [ 'password' => 'letters' ]
        );
        $this->assertFalse($validation->passes());
    }

    public function test_letters_succeeds(): void
    {
        $validation = $this->validation->make(
            [ 'password' => 'T' ],
            [ 'password' => 'letters' ]
        );
        $this->assertTrue($validation->passes());
    }

    public function test_custom_validation_errors_are_not_overwritten(): void
    {
        $validation = $this->validation->make(
            [ 'password' => '' ],
            [ 'password' => 'required' ],
            [ 'required' => 'Should not be overwritten.' ]
        );

        $errorArray = $validation->errors()->get('password');
        $this->assertEquals('Should not be overwritten.', $errorArray[0]);
    }
}

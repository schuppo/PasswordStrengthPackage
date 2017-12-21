<?php

namespace Schuppo\PasswordStrength;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

/**
 * PasswordStrengthTest
 *
 * @group validators
 */
class PasswordStrengthTest extends \PHPUnit_Framework_TestCase
{
    private $validation;

    public function __construct()
    {
        parent::__construct();

        $this->validation = new Factory(new Translator(new ArrayLoader(), 'en'));
    }

    public function setUp()
    {
        $pS = new PasswordStrength();
        $this->validation->extend('symbols', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateSymbols($value);
        });
        $this->validation->extend('case_diff', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateCaseDiff($value);
        });
        $this->validation->extend('numbers', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateNumbers($value);
        });
        $this->validation->extend('letters', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateLetters($value);
        });
    }

    public function test_symbols_fails_no_symbol()
    {
        $validation = $this->validation->make(
            array( 'password' => 'tt' ),
            array( 'password' => 'symbols' )
        );
        $this->assertFalse($validation->passes());
    }

    public function test_symbols_succeeds_with_symbol()
    {
    	$symbols = array(
            '!', '@', '#', '$', '%',
            '^', '&', '*', '?', '(',
            ')', '-', '_, ', '=', '+',
            '{', '}', ';', ':', ',',
            '<', '.', '>', '\\', '/',
            ' ', "\t"
		);

        foreach($symbols as $symbol) {
            $validation = $this->validation->make(
                array( 'password' => $symbol ),
                array( 'password' => 'symbols' )
            );
            $this->assertTrue($validation->passes());
        }
    }

    public function test_case_diff_fails_just_lowercase()
    {
        $validation = $this->validation->make(
            array( 'password' => 'tt' ),
            array( 'password' => 'case_diff' )
        );
        $this->assertFalse($validation->passes());
    }

    public function test_case_diff_fails_just_uppercase()
    {
        $validation = $this->validation->make(
            array( 'password' => 'TT' ),
            array( 'password' => 'case_diff')
        );
        $this->assertFalse($validation->passes());
    }

    /** @test */
    public function it_handles_cyrillic_letters()
    {
        $validation = $this->validation->make(
            array( 'password' => 'Ѐѐ' ),
            array( 'password' => 'case_diff')
        );
        $this->assertTrue($validation->passes());
    }

    public function test_case_diff_succeeds()
    {
        $validation = $this->validation->make(
            array( 'password' => 'Tt' ),
            array( 'password' => 'case_diff')
        );
        $this->assertTrue($validation->passes());
    }

    public function test_numbers_fails()
    {
        $validation = $this->validation->make(
            array( 'password' => 'T' ),
            array( 'password' => 'numbers' )
        );
        $this->assertFalse($validation->passes());
    }

    public function test_numbers_succeeds()
    {
        $validation = $this->validation->make(
            array( 'password' => 1 ),
            array( 'password' => 'numbers' )
        );
        $this->assertTrue($validation->passes());
    }

    public function test_numbers_succeeds_float()
    {
        $validation = $this->validation->make(
            array( 'password' => 1.1 ),
            array( 'password' => 'numbers' )
        );
        $this->assertTrue($validation->passes());
    }

    public function test_letters_fails()
    {
        $validation = $this->validation->make(
            array( 'password' => '1' ),
            array( 'password' => 'letters' )
        );
        $this->assertFalse($validation->passes());
    }

    public function test_letters_succeeds()
    {
        $validation = $this->validation->make(
            array( 'password' => 'T' ),
            array( 'password' => 'letters')
        );
        $this->assertTrue($validation->passes());
    }

    public function test_custom_validation_errors_are_not_overwritten()
    {
        $validation = $this->validation->make(
            array( 'password' => '' ),
            array( 'password' => 'required' ),
            array( 'required' => 'Should not be overwritten.' )
        );

        $errorArray = $validation->errors()->get('password');
        $this->assertEquals('Should not be overwritten.', $errorArray[0]);
    }
}

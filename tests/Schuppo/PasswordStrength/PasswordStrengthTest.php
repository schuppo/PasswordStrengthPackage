<?php namespace Schuppo\PasswordStrength;

use \Symfony\Component\Translation\Translator;
use  \Illuminate\Validation\Factory;
/**
 * PasswordStrengthTest
 *
 * @group validators
 */
class PasswordStrengthTest extends \PHPUnit_Framework_TestCase
{
    /** @var Factory */
    public $factory;

    public function setUp()
    {
        $this->factory = new Factory(new Translator('en'));
        $pS = new PasswordStrength();
        $this->factory->extend('symbols', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateSymbols($attribute, $value, $parameters);
        });
        $this->factory->extend('case_diff', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateCaseDiff($attribute, $value, $parameters);
        });
        $this->factory->extend('numbers', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateNumbers($attribute, $value, $parameters);
        });
        $this->factory->extend('letters', function($attribute, $value, $parameters) use ($pS){
            return $pS->validateLetters($attribute, $value, $parameters);
        });
    }

    public function test_symbols_fails_no_symbol()
    {
        $validation = $this->factory->make(
            array( 'password' => 'tt' ),
            array( 'password' => 'symbols' )
        );
        $this->assertFalse($validation->passes());
    }

    public function test_symbols_succeeds_with_symbol()
    {
        $validation = $this->factory->make(
            array( 'password' => '@' ),
            array( 'password' => 'symbols' )
        );
        $this->assertTrue($validation->passes());
    }


    public function test_case_diff_fails_just_lowercase()
    {
        $validation = $this->factory->make(
            array( 'password' => 'tt' ),
            array( 'password' => 'case_diff' )
        );
        $this->assertFalse($validation->passes());
    }

    public function test_case_diff_fails_just_uppercase()
    {
       $validation = $this->factory->make(
            array( 'password' => 'TT' ),
            array( 'password' => 'case_diff')
        );
        $this->assertFalse($validation->passes());
    }

    /** @test */
    public function it_handles_cyrillic_letters()
    {
        $validation = $this->factory->make(
            array( 'password' => 'Ѐѐ' ),
            array( 'password' => 'case_diff')
        );
        $this->assertTrue($validation->passes());
    }

    public function test_case_diff_succeeds()
    {
        $validation = $this->factory->make(
            array( 'password' => 'Tt' ),
            array( 'password' => 'case_diff')
        );
        $this->assertTrue($validation->passes());
    }

    public function test_numbers_fails()
    {
        $validation = $this->factory->make(
            array( 'password' => 'T' ),
            array( 'password' => 'numbers' )
        );

        $this->assertFalse($validation->passes());
    }

    public function test_numbers_succeeds()
    {
        $validation = $this->factory->make(
            array( 'password' => '1' ),
            array( 'password' => 'numbers' )
        );

        $this->assertTrue($validation->passes());
    }

    public function test_letters_fails()
    {
        $validation = $this->factory->make(
            array( 'password' => '1' ),
            array( 'password' => 'letters' )
        );

        $this->assertFalse($validation->passes());
    }

    public function test_letters_succeeds()
    {
        $validation = $this->factory->make(
            array( 'password' => 'T' ),
            array( 'password' => 'letters')
        );

        $this->assertTrue($validation->passes());
    }

    public function test_custom_validation_errors_are_not_overwritten()
    {
        $validation = $this->factory->make(
            array( 'password' => '' ),
            array( 'password' => 'required' ),
            array( 'required' => 'Should not be overwritten.' )
        );

        $errorArray = $validation->errors()->get('password');
        $this->assertEquals('Should not be overwritten.', $errorArray[0]);
    }
}

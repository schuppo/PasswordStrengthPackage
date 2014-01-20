<?php namespace Schuppo\PasswordStrength;

use \Symfony\Component\Translation\Translator;
use  \Illuminate\Validator;
/**
 * PasswordStrengthTest
 *
 * @group validators
 */
class PasswordStrengthTest extends \PHPUnit_Framework_TestCase
{
    public function test_case_diff_fails_just_lowercase()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => 'tt'],
            ['password' => 'case_diff']
        );
        $this->assertFalse($this->validation->passes());
    }

    public function test_case_diff_fails_just_uppercase()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => 'TT'],
            ['password' => 'case_diff']
        );
        $this->assertFalse($this->validation->passes());
    }

    public function test_case_diff_succeeds()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => 'Tt'],
            ['password' => 'case_diff']
        );


        $this->assertTrue($this->validation->passes());
    }

    public function test_numbers_fails()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => 'T'],
            ['password' => 'numbers']
        );

        $this->assertFalse($this->validation->passes());
    }

    public function test_numbers_succeeds()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => '1'],
            ['password' => 'numbers']
        );

        $this->assertTrue($this->validation->passes());
    }

    public function test_letters_fails()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => '1'],
            ['password' => 'letters']
        );

        $this->assertFalse($this->validation->passes());
    }

    public function test_letters_succeeds()
    {
        $this->validation = new PasswordStrength(new Translator('en'),
            ['password' => 'T'],
            ['password' => 'letters']
        );

        $this->assertTrue($this->validation->passes());
    }

    public function test_custom_validation_errors_are_not_overwritten()
    {
        $this->validation = (new \Illuminate\Validation\Factory(new Translator('en')))->make(
            ['password' => ''],
            ['password' => 'required'],
            ['required' => 'Should not be overwritten.']);
        dd($this->validation->errors());
    }
}

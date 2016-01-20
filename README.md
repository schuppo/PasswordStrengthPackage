PasswordStrength Package
================
[![Build Status](https://travis-ci.org/schuppo/PasswordStrengthPackage.png?branch=master)](https://travis-ci.org/schuppo/PasswordStrengthPackage)
[![Total Downloads](https://poser.pugx.org/schuppo/password-strength/downloads)](https://packagist.org/packages/schuppo/password-strength)
[![License](https://poser.pugx.org/schuppo/password-strength/license)](https://packagist.org/packages/schuppo/password-strength)

This package provides a validator that ensures strong passwords in Laravel 4 applications. It is influenced  a lot by [PasswordStrengthBundle for Symfony 2](https://github.com/jbafford/PasswordStrengthBundle).

It is out now for a while and since there were no complaints it very likely fulfills its purpose.

The provided validations include:

- check if input contains alphabetic characters
- check if input contains numeric characters
- check if input contains mixed case characters
- check if input contains symbols

#Documentation

##Installation

### Get the package

**For Laravel 4 users**

Add the following to your `composer.json`:

```json
{
    "require": {
        "schuppo/password-strength": "~0.7"
    }
}
```
and afterwards ```composer update```.

Or just ```composer require schuppo/password-strength:"~0.7@dev"```.

**For Laravel 5 users**

```json
{
    "require": {
        "schuppo/password-strength": "~1.0"
    }
}
```
and afterwards ```composer update```.

Or just ```composer require schuppo/password-strength:"~1.0@dev"```.

### Initialize the package

To start using the package, you have to add it to the array key `providers` in `app/config/app.php`:

```php
// app/config/app.php
return array(
    // ...
    'providers' => array(
        // ...
        \Schuppo\PasswordStrength\PasswordStrengthServiceProvider::class,
    );
    // ...
);
```

**Caution**

I recently recognized a small conflict in the usage of this package in combination with [unique-with](https://github.com/felixkiss/uniquewith-validator): One runs into problems when adding the ```PasswordStrengthServiceProvider``` **after** ```UniqueWithValidatorServiceProvider``` to the providers array, the  rules of this package stay unknown to the Laravel ```Validator```.

The problem is easy to fix though: Just add the service provider of this package in front of the service provider of *unique-with*. In that order both packages work fine.

##Usage
Now Laravel's native `Validator` is extended by four rules:

- case_diff
- numbers
- letters
- symbols

### Example
You can apply these rules as described in the [validation section on Laravel's website](http://laravel.com/docs/validation)

```php
$v = Validator::make(
    'password' => '12345QWERTqwert@',
    'password' => 'case_diff|numbers|letters|symbols')
);
$v->passes();   // returns true;
```

Notice that you can validate any value with the new rules. The only reason why this package is called "Password Strength Package" is that it describes its foremost purpose.

#History

*Laravel 5 related*

**[1.5]**

- Adds unicode flag to case difference validation rule  

**[1.4]**

- Adds dutch translation
- Updates french translation
- Makes packages php7 ready

**[1.3]**

Adds romanian translation

**[1.2]**

Adds polish translation

**[1.1]**

Adds french translation

**[1.0.2]**

Updates README.md

**[1.0.1]**

Starts to make package laravel 5 ready

*Laravel 4 related*

**[0.10]**

- Adds unicode flag to case difference validation rule  


**[0.9]**

- Adds dutch translation
- Updates french translation
- Makes packages php7 ready

**[0.8.2]**

Adds romanian translation

**[0.8.1]**

Adds polish translation

**[0.8]**

Adds french translations

**[0.7]**

Fixes typo in readme

**[0.6]**

- New validation rule to check if input contains symbols. Thanks to [closca](https://github.com/closca) for providing this new feature.

**[0.5.3]**

- Added new version to composer.json

**[0.5.2]**

- Small changes in README.md

**[0.5.1]**

- Minimum requirement (PHP 5.4 because of array chains) is now recognized by composer.json

**[0.4.1]**

- The package works properly now when other extensions of laravel's validator are used as well (like [unique-with](https://github.com/felixkiss/uniquewith-validator)).
- The package is able to take localization overwrites now as described in the [laravel docs](http://laravel.com/docs/localization#overriding-package-language-files)

**[0.3.1]**

- Fixed: Package validator doesn't overwrite custom validation errror messages any more. Not functional tested though because I have no clue how to set up a test which controls the passing of variables from the password strength package to the native validator INSIDE the package's test folders. Any suggestions?

#License

This package is under the MIT license. See the complete license:

- [LICENSE](https://github.com/schuppo/PasswordStrengthPackage/LICENSE)


##Reporting Issues or Feature Requests

Issues and feature requests are tracked on [GitHub](https://github.com/schuppo/PasswordStrengthPackage/issues).

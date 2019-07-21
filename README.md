# Slug field for Laravel Nova

[![Latest Version on Packagist](https://img.shields.io/packagist/v/drobee/nova-sluggable.svg?style=flat-square)](https://packagist.org/packages/drobee/nova-sluggable)

Slug field for Laravel Nova that can generate unique slug for your model while typing.

![nova-sluggable demo](https://drobee.github.io/nova-sluggable.gif)

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require drobee/nova-sluggable
```



## Usage

Add a new `SluggableText` and a `Slug` field to your Nova Resource:

```php
use Drobee\NovaSluggable\SluggableText;
use Drobee\NovaSluggable\Slug;

class User extends Resource
{
    // ...

    public function fields(Request $request)
    {
        return [
            // ...

            SluggableText::make('Title'),
            Slug::make('Slug'),

            // ...
        ];
    }
}
```

When the user types a string in the `SluggableText` field the value is being sent the API to generate the slug and and then it sets the `Slug` field's value to the that. The slug is updated on every `key up` event, but it can be tied to `blur` event on the title field.

By default it looks for a `Slug` type field with the name Slug.

In order to work properly every defined `SluggableText` field need a corresponding `Slug` field.

## Options

#### Slug field with custom name

Set the `Slug` fields name on the `SluggableText` field with the `slug()` method:

```php
SluggableText::make('Title')->slug('SEO Title');
Slug::make('SEO Title', 'slug');
```

#### Language

Set the language to use for the generation with the `Slug` field's `slugLanguage()` method:

```php
Slug::make('Slug')->slugLanguage('hu');
```

Default value: `en`

#### Maximum length

Limit the maximum length of the generated slug with the `Slug` field's `slugMaxLength()` method:

```php
Slug::make('Slug')->slugMaxLength(100);
```

Default value: `255`

#### Maximum length

Set the string all the whitespaces will be replaced with with the `Slug` field's `slugSeparator()` method:

```php
Slug::make('Slug')->slugSeparator('.');
```

Default value: `-`

Note: the generated slug may be few characters longer than the value specified, due to the suffix which is added to make it unique.

#### Update event

By default the slug updates on every `keyup` event, but you can tie it to the `blur` event:

```php
Slug::make('Slug')->event('blur');
```

Accepted values: `keyup`, `blur`

Default value: `keyup`

#### Unique slug and Eloquent model

The generated slugs won't be unique unless you call the `slugUnique()` method on your `Slug` field.

You also need to specify what Eloquent model should the generator use to make the slug unique by calling the `slugModel()` method. In most cases you would want to use the same Eloquent model as your resource. To do so call the method with the resource's static `$model` attribute. 

```php
Slug::make('Slug')
    ->slugUnique()
    ->slugModel(static::$model);
```

When these to options are set the generated slug will be unique on the set model regarding the attribute value of the `Slug` field.

#### Usage with [Spatie\Sluggable](https://github.com/spatie/laravel-sluggable)

If the Eloquent model you specified with `slugModel()` uses `Spatie\Sluggable`'s `HasSlug` trait and implements its `getSlugOptions()` method, then you don't have to set the separator, maximum length or language for the field. In such cases the generator uses the values you already set on your model.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email robi@dpb.hu instead of using the issue tracker.

## Credits

- [Robert Dezso](https://github.com/drobee)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Acknowledgments

Special thanks to:

- [Spatie](https://github.com/spatie) for [laravel-sluggable](https://github.com/spatie/laravel-sluggable)
- [Benjamin Hirsch](https://github.com/benjaminhirsch) for [nova-slug-field](https://github.com/benjaminhirsch/nova-slug-field) 

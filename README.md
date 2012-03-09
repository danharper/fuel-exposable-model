# Fuel Exposable Model

FuelPHP package to ease in exposing model properties on your API.

This package supplies you with an extension of Fuel's default `Orm\Model`: `Exposable\Model`. Exposable Model provides your model with additional functionality for exposing certain model properties on an API.

I recently built a RESTful API atop Fuel and found specifying fields to expose via the API to be painful, so wrote a few helper methods to allow each resource to specify exactly which fields to expose, along with the ability to expose computed/dynamic fields.

## Installation
1. Clone or download the repo into your app's `fuel/packages/` directory.
2. Add `fuel-exposable-model` (and `orm` if it's not there already) to the array of packages to always load in `fuel/app/config/config.php`

## Usage

To use, simply change the class your Model extends from `Orm\Model` to `Exposable\Model`, eg:

```php
<?php
// before
class Model_User extends Orm\Model {

// after
class Model_User extends Exposable\Model {
```

Now in your Model, set the properties you wish to expose:

```php
<?php
protected static $_exposable_properties = array(
	'id',
	'username',
	'email',
	'location',
	'first_name',
	'last_name',
);
```

And optionally define a function which returns an array of additional "computed" properties to expose. This function is passed an individual model:

```php
<?php
protected static function _exposable_computed($model)
{
	return array(
		'name' => $model->first_name.' '.$model->last_name,
	);
}
```

You can now expose the properties you've specified by passing a model or array of models:

```php
<?php
$exposed = Model_User::expose( Model_User::find_all() );
```

An example API method in your Rest_Controller could be:

```php
<?php
public function get_index()
{
	$models = Model_User::find_all();
	$expose = Model_User::expose($models);
	return $this->response($expose, 200);
}
```

### Second Level Exposures

You may need to expose certain properties from a model's relation. This can be done by passing an array as a property, with the key as the name of the related model.

For example, to expose a few of the user's billing details when exposing the user, you could do:

```php
<?php
protected static $_exposable_properties = array(
	'id',
	'username',
	'email',
	'location',
	'first_name',
	'last_name',
	'billing' => array(
		'address',
		'city',
		'country',
	),
);
```

In JSON form, this would look like:

```js
{
	id: 1,
	username: 'danharper',
	email: 'test@example.com',
	location: 'Portsmouth, UK',
	first_name: 'Dan',
	last_name: 'Harper',
	billing: {
		address: '92 Lorem Ipsum St',
		city: 'Portsmouth',
		country: 'United Kingdom'
	},
	name: 'Dan Harper'
}
```

Optionally, you could flatten the second level by passing `__flatten` as the first property in the array:

```js
<?php
protected static $_exposable_properties = array(
	'id',
	'username',
	'email',
	'location',
	'first_name',
	'last_name',
	'billing' => array(
		'__flatten',
		'address',
		'city',
		'country',
	),
);
```

Which would result in:

```js
{
	id: 1,
	username: 'danharper',
	email: 'test@example.com',
	location: 'Portsmouth, UK',
	first_name: 'Dan',
	last_name: 'Harper',
	address: '92 Lorem Ipsum St',
	city: 'Portsmouth',
	country: 'United Kingdom',
	name: 'Dan Harper'
}
```

## An Example

As a run-down, imagine we have a Users resource with the following fields:

- id
- username
- email
- password
- location
- first_name
- last_name
- last_login_ip
- created_at
- updated_at

On our API we want to expose this data, but let's say we don't want to expose the `password`, `last_login_ip`, `created_at` or `updated_at` fields. In our Model we can specify exactly which fields to expose with the `$_exposable_properties` variable:

```php
<?php
protected static $_exposable_properties = array(
	'id',
	'username',
	'email',
	'location',
	'first_name',
	'last_name',
);
```

We can optionally also expose computed/dynamic fields by specifying an `_exposable_computed` _function_ in our Model which returns an array with additional fields to merge in. For example, let's also expose a `name` field which combines the first and last name fields:

```php
<?php
protected static function _exposable_computed($model)
{
	return array(
		'name' => $model->first_name.' '.$model->last_name,
	);
}
```

Exposing our specified fields is now as simple as doing something like:

```php
<?php
public function get_index()
{
	$models = Model_User::find_all();
	$expose = Model_User::expose($models);
	return $this->response($expose, 200);
}
```
# Fuel S3

A FuelPHP package wrapping around [Donovan Schönknecht](http://github.com/tpyo/)'s [Amazon S3 PHP class](http://github.com/tpyo/amazon-s3-php-class).

## Usage

Copy `config/s3.php` into your `app/config/` directory and set your Access & Secret Access keys and default ACL level.

You can now call any of the [class methods](http://undesigned.org.za/2007/10/22/amazon-s3-php-class/documentation) on the `S3` object. For example:

```php
S3::listBuckets();
```

### ACL Levels
When setting a custom ACL level, set it as a String, not a Constant as described the [class methods](http://undesigned.org.za/2007/10/22/amazon-s3-php-class/documentation). For example:

```php
S3::putObject($file, $bucket, $name, 'ACL_PUBLIC_READ');
```
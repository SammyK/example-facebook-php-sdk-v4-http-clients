# Facebook PHP SDK v4 :: HTTP Client Injection

[![License](http://img.shields.io/badge/license-MIT-lightgrey.svg)](https://github.com/SammyK/example-facebook-php-sdk-v4-http-clients/blob/master/README.md#license)


You can overwrite the default curl implementation of the Facebook PHP SDK v4 by coding to the `Facebook\FacebookHttpable` interface. Then you can easily inject it like so:

```php
FacebookRequest::setHttpClientHandler(new MyCustomHttpClient());
```

Here are a few examples of how to inject different HTTP clients:

- [Guzzle v4](https://github.com/SammyK/example-facebook-php-sdk-v4-http-clients/blob/master/index_guzzle.php) Implementation
- [Stream Wrapper](https://github.com/SammyK/example-facebook-php-sdk-v4-http-clients/blob/master/index_stream.php) Implementation


## Composer Setup

Client injection was made possible in the latest SDK version 4. Make sure you're using the latest SDK version in your `composer.json` file.

```json
{
    "require": {
        "facebook/php-sdk-v4": "dev-master"
    }
}
```


If you want to run the Guzzle v4 implementation, you'll need to add that to your `composer.json` file as well:

```json
{
    "require": {
        "guzzlehttp/guzzle": "~4.0"
    }
}
```


## License

The MIT License (MIT)

Copyright (c) 2014 Sammy Kaye Powers <sammyk@sammykmedia.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

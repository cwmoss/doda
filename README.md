# doda

load your domain specific data from files or functions, lazy or not

## why?

i need a fast and simple way to load rather static read only data in my app. that kind of data i don't want to have in a database. maybe the app doesn't need a database anyways. 

### features

* support multiple data formats: yaml, json, ini, serialized php
* support lazy load from files
* support lazy load from anonymous functions 
* support cache (serialized php as the fastest format for deserialization)

## install 

this package depends on `"symfony/yaml"`

    composer require cwmoss/doda

## usage

    require_once("vendor/autoload.php");
    use cwmoss\doda;

    $domain = new doda(__DIR__.'/config/domain-data.yaml');

    $country = $domain->get('country_codes.fr'); // "France"

the example yaml file

    contact_options:
        - via email
        - via phone
    contact_phone: 555 321654
    country_codes: !file cc.json

## api

### constructor()

    new doda($entrypoint_file, (optional)[array_of_callback_functions]);

the `entrypoint_file` should either be a yaml file or a cache file.

### get($path, $default=null)

`$path` is dot.notated.path to your data or an array of path segments

    # "country_code.fr" is the same as ['country_code', 'fr']

the default value will be returned, if a key in the path does not exist in your data.

### write_cache()

you can php-serialize a previously parsed file, which then can be used as `entrypoint_file`.

you can compile from command line:

    php vendor/cwmoss/doda/src/doda.php config/your-domain-data-file.yaml

## yaml specific

### the `import` key

your yamlfile can contain the top-level-magic-key `import`. here you can list all the data, you wish to load and then merge with the rest of the file.

    import:
        - imports/categories.yaml
        - imports/countries.db
        - ../fruits-folder/fruits.php

### !file

this yaml tag loads data from a file at the time, you want to access this data (lazy load).

    country_codes: !file cc.json

see `tests/data` folder for more examples

### !fun

this yaml tag loads data from a function call at the time, you want to access this data (lazy load).
the function name should be in the `$functions` array during instantiation.

    country_codes: !fun load_country_codes

see `tests/data` folder for more examples

## tests

run php unit

    phpunit

## license

`cwmoss/doda` is released under the MIT public license. See the enclosed `LICENSE` for details.
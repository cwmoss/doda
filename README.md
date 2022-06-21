# doda

load your domain spcific data from files or functions

## install 

this package depends on `"symfony/yaml"`

    composer require cwmoss/doda

## usage

    require_once("vendor/autoload.php");
    use cwmoss\doda;

    $domain = new doda(__DIR__.'/config/domain-data');
    $domain->parse();

    $country = $domain->get('country_codes.fr'); // "France"

the example yaml file

    contact_options:
        - via email
        - via phone
    country_codes: !file countrycodes.json

## api

### constructor()

    new doda($entry_file_without_file_ending, [array_of_callback_functions] (optional));

### parse()

parses the yaml file

### load()

alternatively you can load a previously "compiled" .db file, that contains all imported data as a php serialized string

### get($path, $default=null)

`$path` is dot.notated.path to your data or an array of path segments

    # "country_code.fr" is the same as ['country_code', 'fr']

### compile($write_file=false)

you can php-serialize a previously parsed file, which then can be used via `load()` function.

you can compile from command line:

    php vendor/cwmoss/doda/src/doda.php config/your-domain-data-file-without-file-ending

## yaml specific

### the `import` key

your yamlfile can contain the top-level-magic-key `import`. here you can list all the data, you wish to load and then merge with the rest of the file.

    import:
        - imports/categories.yaml
        - imports/countries.db
        - ../fruits-folder/fruits.php

### !file

this yaml tag loads data from a file at the time, you want to access this data (lazy load).

    country_codes: !file countrycodes.json

see `tests/data` folder for more examples

### !fun

this yaml tag loads data from a function call at the time, you want to access this data (lazy load).
the function name should be in the `$functions` array during instantiation.

    country_codes: !fun load_countrycodes

see `tests/data` folder for more examples

## tests

run php unit

    phpunit

## license

`cwmoss/doda` is released under the MIT public license. See the enclosed `LICENSE` for details.
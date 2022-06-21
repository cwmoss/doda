# doda

load your domain data from files or functions

## install & usage

this package depends on `"symfony/yaml"`

    $ composer require cwmoss/doda

    require_once("vendor/autoload.php");
    use cwmoss\doda;

    $domain = new doda(__DIR__.'/domain-data');
    $domain->parse();

    $country = $domain->get('countries.fr'); // "France"

## api

### constructor()

    new doda($entry_file_without_file_ending, [array_of_callback_functions] (optional));

### parse()

    parses the yaml file

### load()

    alternatively you can load a previously "compiled" .db file, that contains all imported data as a php serialized string

### get($path, $default=null)

    $path is dot-notated path to your data or an array of segments

    ex: country_code.fr is the same as ['country_code', 'fr']

### compile($write_file=false)

you can php-serialize a previously parsed file, which then can be used via `load()` function.

you can compile from command line:

    $ your-project-root $ php vendor/cwmoss/doda/src/doda.php config/your-domain-data-file-without-file-ending

## yaml specific

your yamlfile can contain the top-level-magic-key `import`. here you can list all the data, you wish load and then merge with the rest of the file.

### !file

this yaml tag loads data from a file at the time, you want to access this data (lazy load).

    ex: country_codes: !file countrycodes.json

see tests/data folder for more examples

### !fun

this yaml tag loads data from a function call at the time, you want to access this data (lazy load).
the function name should be in the `$functions` array during instantiation.

    ex: country_codes: !fun load_countrycodes

see tests/data folder for more examples



<?php

namespace cwmoss;

class doda
{
    public $entry_point;
    public $functions = [];
    public $data = [];

    public function __construct($entry_point, $functions=[])
    {
        $this->entry_point = $entry_point;
        $this->functions = $functions;
        $this->data = $this->load_file($entry_point);
    }

    public function get($path, $default=null)
    {
        if (!is_array($path)) {
            $path = explode('.', $path);
        }
        $current = $this->data;
        $current_path = [];

        foreach ($path as $part) {
            $current_path[] = $part;
            if (is_array($current) && array_key_exists($part, $current) && is_array($current[$part]) && array_key_exists('__tag__', $current[$part])) {
                $newdata = $this->resolve($current[$part]['__tag__'], $current_path);
                $this->update_data($current_path, $newdata);
                $current = $newdata;
                continue;
            }

            if (is_array($current) && array_key_exists($part, $current)) {
                $current = $current[$part];
            } else {
                return $default;
            }
        }
        return $current;
    }

    public function resolve($tag, $path)
    {
        $m = 'resolve_'.$tag[0];
        if (method_exists($this, $m)) {
            return $this->$m($tag[1], $path);
        }
    }

    public function resolve_fun($name, $path)
    {
        #print "resolve function $name\n";
        return $this->functions[$name]($path);
    }

    public function resolve_file($name, $path)
    {
        return $this->load_file($name, dirname($this->entry_point));
    }

    public function update_data($path, $data)
    {
        if (!is_array($path)) {
            $path = explode('.', $path);
        }


        $current = &$this->data;
        foreach ($path as $part) {
            if (!isset($current[$part])) {
                $current[$part] = [];
            }
            $current = &$current[$part];
        }
        $current = $data;
    }

    public function write_cache()
    {
        #print_r($this);
        $cache = substr_replace(
            $this->entry_point,
            'cache',
            strrpos($this->entry_point, '.') +1
        );

        file_put_contents($cache, serialize($this->data));
    }

    public function load_file($file, $basedir=".")
    {
        $info = pathinfo($file);

        $parser = "load_{$info['extension']}";
        if ($file[0]=="/") {
            $fname = $file;
        } else {
            $fname = $basedir.'/'.$file;
        }
        if ($parser=='load_php') {
            return $this->load_php($fname);
        }
        $content = file_get_contents($fname);
        return $this->$parser($content, $info['dirname']);
    }

    public function load_yaml($content, $base="")
    {
        $parsed = \Symfony\Component\Yaml\Yaml::parse($content, \Symfony\Component\Yaml\Yaml::PARSE_CUSTOM_TAGS);
        $imports = [];
        if (array_key_exists('import', $parsed)) {
            $imports = is_array($parsed['import']) ? $parsed['import'] : [$parsed['import']];
            unset($parsed['import']);
        }

        array_walk_recursive($parsed, function (&$value) {
            if ($value instanceof \Symfony\Component\Yaml\Tag\TaggedValue) {
                $value = ['__tag__'=>[$value->getTag(), $this->compile_value($value->getTag(), $value->getValue())]];
            }
        });
        if (!$imports) {
            return $parsed;
        }
        $parsed_imports = [];
        foreach ($imports as $file) {
            $parsed_imports = array_merge($parsed_imports, $this->load_file($file, $base));
        }
        return array_merge($parsed_imports, $parsed);
    }

    public function load_cache($content, $base="")
    {
        return unserialize($content);
    }

    public function load_db($content, $base="")
    {
        return unserialize($content);
    }

    public function load_json($content, $base="")
    {
        return json_decode($content, true);
    }
    public function load_php($file)
    {
        return include($file);
    }
    public function load_ini($content, $base="")
    {
        return parse_ini_string($content, true);
    }

    public function compile_value($tag, $val)
    {
        return $val;
    }
}

// most simple cache write
// your-project-root $ php vendor/cwmoss/doda/src/doda.php config/your-domain-data-file.yaml
if (php_sapi_name() == 'cli' && isset($argv[1]) && realpath($argv[0]) == realpath(__FILE__)) {
    include_once("vendor/autoload.php");
    (new doda(realpath($argv[1])))->write_cache();
}

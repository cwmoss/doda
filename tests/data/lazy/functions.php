<?php

return [
    'fruits' => function () {
        return include("fruits.php");
    },

    'cities' => function () {
        return json_decode(file_get_contents(__DIR__."/cities.json"), true);
    },
    
    'countries' => function () {
        return unserialize(file_get_contents(__DIR__."/countries.db"));
    },

    'load_smth' => function ($what) {
        if ($what[0]=='categories') {
            return [
                'sex', 'drugs', "rock'n'roll"
            ];
        }
        return parse_ini_file('colors.ini');
    }
];

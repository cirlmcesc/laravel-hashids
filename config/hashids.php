<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Salt String
    |--------------------------------------------------------------------------
    |
    | This salt string is used for generating HashIDs and should be set
    | to a random string, otherwise these generated HashIDs will not be
    | safe. Please do this definitely before deploying your application!
    |
    | Default: larvelhashids
    */

    "salt" => env('APP_KEY', 'larvelhashids'),

    /*
    |--------------------------------------------------------------------------
    | Raw HashID Length
    |--------------------------------------------------------------------------
    |
    | This is the length of the raw HashID. The model prefix, separator
    | and the raw HashID are combined all together. So the Model HashID
    | length is the sum of raw HashID, separator, and model prefix lengths.
    |
    | Default: 16
    |
    */

    "length" => env('HASH_LENGTH', 16),

    /*
    |--------------------------------------------------------------------------
    | HashID Alphabet
    |--------------------------------------------------------------------------
    |
    | This alphabet will generate raw HashIDs. Please keep in mind that it
    | must contain at least 16 unique characters and can't contain spaces.
    | 
    | This code was written with the intent of placing the output ids in visible places, 
    | like the URL. Therefore, the algorithm tries to avoid generating most common English 
    | curse words by generating ids that never have the following letters next to each other:
    | 
    | Letters that should not appear: c, f, h, i, s, t, u
    |
    | Default: 'abdegjklmnopqrtvwxyzABDEGJKLMNOPQRVWXYZ'
    |
    */

    'alphabet' => env('HASH_ALPHABET', 'abdegjklmnopqrtvwxyzABDEGJKLMNOPQRVWXYZ'),

    /*
    |--------------------------------------------------------------------------
    | Hash method
    |--------------------------------------------------------------------------
    |
    | Set as the default encryption method, whether it is hexadecimal encryption.
    | Normal encryption is used by default.
    |
    | Supported: "normal", "hexadecimal"
    | Default: "normal"
    |
    */

    'method' => config('HASH_METHOD', 'normal'),

];
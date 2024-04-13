<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Database operations chunk count (int)
     |--------------------------------------------------------------------------
     |
     | How many chunks should database helper operations count on per batch?
     |
     */

    'databasing_chunks' => 500,

    /*
     |--------------------------------------------------------------------------
     | Database insertion default properties (array)
     |--------------------------------------------------------------------------
     |
     | Since it's manual insertion, all attributes must be considered. And these
     | ones are appended as defaults. (Rest are in the method's $dataArrays)
     |
     */

    'insertion_default_properties' => [
        'created_at' => now(),
        'updated_at' => now(),
    ],

    
    /*
     |--------------------------------------------------------------------------
     | Hash digits count (int)
     |--------------------------------------------------------------------------
     |
     | How many digits should generated unique hashes occupy?
     |
     */

    'hash_digits_count' => 8,

    /*
     |--------------------------------------------------------------------------
     | String-hash separator (string)
     |--------------------------------------------------------------------------
     |
     | How would appended hashes get separated? Example: 'someString #283849502'
     |
     */

    'string_hash_separator' => ' #',

];

<?php

namespace App\Service;

class Slugify
{
    public function generate(string $input) : string
    {
        $input = str_replace(' ', '-', $input);
        $input = str_replace('à', 'a', $input);
        $input = str_replace('ç', 'c', $input);
        $input = str_replace('?', '', $input); 
        $input = str_replace('!', '', $input); 
        $input = str_replace('\'', '', $input); 
        $input = str_replace('\,', '', $input); 
        $input = str_replace('\;', '', $input); 
        $input = str_replace('&', '', $input); 
        $input = str_replace('$', '', $input); 
        for ($i = 0; $i < strlen($input); $i++) {
            $input = str_replace('--', '-', $input); 
        }
        $input = strtolower($input);
        $input = trim($input);

        return $input;
    }

}
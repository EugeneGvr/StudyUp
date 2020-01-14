<?php

namespace App\Traits;

trait StringGenerator
{
    public function generateString($length = 10, $withNumbers = true, $withUpper = true, $onlyUpper = false)
    {
        $characters = config('app')['characters'];
        $charactersLists = $withUpper && $onlyUpper ? strtoupper($characters) : $characters;

        if ($withUpper && !$onlyUpper) {
            $charactersLists = array_merge($charactersLists, strtoupper($characters));
        }
        if ($withNumbers) {
            $charactersLists = array_merge($charactersLists, $characters = config('app')['numbers']);
        }
        $charactersLength = strlen($charactersLists);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $charactersLists[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

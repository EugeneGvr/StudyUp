<?php

use App\Locality;
use Illuminate\Database\Seeder;

class LocalitiesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Locality::create([
        'code' => '001',
        'name' => 'Eugene',
        'parent_code' => '000',
        'center' => true,
        'type' => 'С'
    ]);
    }

    public function Gene()
    {
        $json = file_get_contents(public_path().'/json/koatuu.json');
        $arr = json_decode($json);
        foreach ($arr->level1 as $elementLevel1) {
            if(!array_key_exists('type', $elementLevel1) || $elementLevel1->type != "") {
                pr($elementLevel1->name);
            }
            foreach ($elementLevel1->level2 as $elementLevel2) {
                if((!array_key_exists('type', $elementLevel2) || $elementLevel2->type != "") && !preg_match("/^МІСТА /", $elementLevel2->name) && !preg_match("/^РАЙОНИ /", $elementLevel2->name)) {
                    pr("\t".$elementLevel2->name);
                }
                foreach ($elementLevel2->level3 as $elementLevel3) {
                    if(!array_key_exists('type', $elementLevel3) || ($elementLevel3->type != "" && $elementLevel3->type != "Р")) {
                        pr("\t\t".$elementLevel3->name);
                    }
                    foreach ($elementLevel3->level4 as $elementLevel4) {
                        if(!array_key_exists('type', $elementLevel4) || $elementLevel4->type != "") {
                            pr("\t\t\t".$elementLevel4->name);
                        }
                    }
                }
            }
        }
    }
}

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
        $json = file_get_contents(public_path().'/json/koatuu.json');
        $arr = json_decode($json);
        $a = true;
        $b = true;
        $code = "";
        $type = "";
        $center1 = "";
        $center2 = "";
        foreach ($arr->level1 as $elementLevel1) {
            $list = explode("/", $elementLevel1->name);
            if(count($list) == 1) {
                $type = "М";
                $center1 = "";
            }
            else {
                $type = "О";
                $center1 = preg_split("/[\s.]+/", $list[1])[1];
            }
            Locality::create([
                'code' => $elementLevel1->code,
                'name' => $list[0],
                'parent_code' => '0000000000',
                'center' => ($list[0] == "М.КИЇВ"),
                'type' => $type
            ]);
            foreach ($elementLevel1->level2 as $elementLevel2) {
                $a = (!array_key_exists('type', $elementLevel2) || $elementLevel2->type != "") && !preg_match("/^МІСТА /", $elementLevel2->name) && !preg_match("/^РАЙОНИ /", $elementLevel2->name);
                if($a) {
                    $list = explode("/", $elementLevel2->name);
                    if(count($list) == 2) {
                        $type = "Р";
                        $center2 = preg_split("/[\s.]+/", $list[1])[1];
                    }
                    else {
                        if(!array_key_exists('type', $elementLevel2)) $type = "М";
                        else $type = $elementLevel2->type;
                        $center2 = "";
                    }
                    Locality::create([
                        'code' => $elementLevel2->code,
                        'name' => $list[0],
                        'parent_code' => $elementLevel1->code,
                        'center' => ($list[0] == $center1),
                        'type' => $type
                    ]);
                }
                if(array_key_exists('level3', $elementLevel2)) {
                    foreach ($elementLevel2->level3 as $elementLevel3) {
                        $b = !array_key_exists('type', $elementLevel3) || ($elementLevel3->type != "" && $elementLevel3->type != "Р");
                        if ($b) {
                            if ($a) $code = $elementLevel2->code;
                            else $code = $elementLevel1->code;
                            if (!array_key_exists('type', $elementLevel3)) $type = "М";
                            else $type = $elementLevel3->type;
                            Locality::create([
                                'code' => $elementLevel3->code,
                                'name' => $elementLevel3->name,
                                'parent_code' => $code,
                                'center' => ($elementLevel3->name == $center2),
                                'type' => $type
                            ]);
                        }
                        if(array_key_exists('level4', $elementLevel3)) {
                            foreach ($elementLevel3->level4 as $elementLevel4) {
                                if ($b) $code = $elementLevel3->code;
                                else {
                                    if ($a) $code = $elementLevel2->code;
                                    else $code = $elementLevel1->code;
                                    if (!array_key_exists('type', $elementLevel4)) $type = "М";
                                    else $type = $elementLevel4->type;
                                }
                                Locality::create([
                                    'code' => $elementLevel4->code,
                                    'name' => $elementLevel4->name,
                                    'parent_code' => $code,
                                    'center' => false,
                                    'type' => $type
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}

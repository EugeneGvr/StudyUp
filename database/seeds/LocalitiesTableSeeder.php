<?php

use App\Models\Locality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

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
       Log::info('Start Running LocalityTableSeeder');
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
            $localityElementLevel1 = new Locality();
            $localityElementLevel1->code         = $elementLevel1->code;
            $localityElementLevel1->name         = $list[0];
            $localityElementLevel1->parent_id    = '0';
            $localityElementLevel1->center       = ($list[0] == "М.КИЇВ");
            $localityElementLevel1->type         = $type;
            $localityElementLevel1->save();
<<<<<<< HEAD
=======
            Log::info('Locality "' . $list[0] . '" is uploaded');
//            Locality::create([
//                'code' => $elementLevel1->code,
//                'name' => ucfirst(strtolower($list[0])),
//                'parent_code' => '0000000000',
//                'center' => ($list[0] == "М.КИЇВ"),
//                'type' => $type
//            ]);
>>>>>>> b842e7544eaac50a8be2c0dbeee48de5f58b523e
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
                    $localityElementLevel2 = new Locality();
                    $localityElementLevel2->code         = $elementLevel2->code;
                    $localityElementLevel2->name         = $list[0];
                    $localityElementLevel2->parent_id    = $localityElementLevel1->id;
                    $localityElementLevel2->center       = ($list[0] == $center1);
                    $localityElementLevel2->type         = $type;
                    $localityElementLevel2->save();
<<<<<<< HEAD
=======
                    Log::info('Locality "' . $list[0] . '" is uploaded');
//                    Locality::create([
//                        'code' => $elementLevel2->code,
//                        'name' => $list[0],
//                        'parent_code' => $elementLevel1->code,
//                        'center' => ($list[0] == $center1),
//                        'type' => $type
//                    ]);
>>>>>>> b842e7544eaac50a8be2c0dbeee48de5f58b523e
                }
                if(array_key_exists('level3', $elementLevel2)) {
                    foreach ($elementLevel2->level3 as $elementLevel3) {
                        $b = !array_key_exists('type', $elementLevel3) || ($elementLevel3->type != "" && $elementLevel3->type != "Р");
                        if ($b) {
                            if ($a) $code = $elementLevel2->code;
                            else $code = $elementLevel1->code;
                            if (!array_key_exists('type', $elementLevel3)) $type = "М";
                            else $type = $elementLevel3->type;
                            $localityElementLevel3 = new Locality();
                            $localityElementLevel3->code         = $elementLevel3->code;
                            $localityElementLevel3->name         = $elementLevel3->name;
                            $localityElementLevel3->parent_id    = $localityElementLevel2->id;
                            $localityElementLevel3->center       = ($elementLevel3->name == $center2);
                            $localityElementLevel3->type         = $type;
                            $localityElementLevel3->save();
<<<<<<< HEAD
=======
                            Log::info('Locality "' . $elementLevel3->name . '" is uploaded');
//                            Locality::create([
//                                'code' => $elementLevel3->code,
//                                'name' => $elementLevel3->name,
//                                'parent_code' => $code,
//                                'center' => ($elementLevel3->name == $center2),
//                                'type' => $type
//                            ]);
>>>>>>> b842e7544eaac50a8be2c0dbeee48de5f58b523e
                        }
                        if(array_key_exists('level4', $elementLevel3)) {
                            foreach ($elementLevel3->level4 as $elementLevel4) {
                                if ($b) {
                                    $id = $localityElementLevel3->id;
                                }
                                else {
                                    $id = $a ? $localityElementLevel2->id : $localityElementLevel1->id;
                                    $type = (!array_key_exists('type', $elementLevel4)) ?
                                        "М" :
                                        $elementLevel4->type;
                                }
                                $localityElementLevel4 = new Locality();
                                $localityElementLevel4->code         = $elementLevel4->code;
                                $localityElementLevel4->name         = $elementLevel4->name;
                                $localityElementLevel4->parent_id    = $id;
                                $localityElementLevel4->center       = false;
                                $localityElementLevel4->type         = $type;
                                $localityElementLevel4->save();
<<<<<<< HEAD
=======
                                Log::info('Locality "' . $elementLevel4->name . '" is uploaded');
//                                Locality::create([
//                                    'code' => $elementLevel4->code,
//                                    'name' => $elementLevel4->name,
//                                    'parent_code' => $code,
//                                    'center' => false,
//                                    'type' => $type
//                                ]);
>>>>>>> b842e7544eaac50a8be2c0dbeee48de5f58b523e
                            }
                        }
                    }
                }
            }
        }
        Log::info('End Running LocalityTableSeeder');
    }
}

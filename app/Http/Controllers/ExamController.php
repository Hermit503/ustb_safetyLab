<?php

namespace App\Http\Controllers;

use App\Exam;
use App\Imports\ExcelImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


use Maatwebsite\Excel\Facades\Excel;


class ExamController extends Controller
{
    /**
     * 上传excel 保存到数据库
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author hzj
     * @date 2020-02-06
     */
    public function upload(Request $request)
    {
        $arrays = Excel::toArray(new ExcelImport(), 'file/exam.xlsx');
        for ($i = 0; $i < count($arrays[0]); $i++) {
            $exam = new Exam();
            $exam->type = $arrays[0][$i][0];
            $exam->question = $arrays[0][$i][1];
            $exam->option1 = $arrays[0][$i][2];
            $exam->option2 = $arrays[0][$i][3];
            $exam->option3 = $arrays[0][$i][4];
            $exam->option4 = $arrays[0][$i][5];
            $exam->answer = $arrays[0][$i][6];
            $exam->save();
        }
        return response()->json('上传成功', 200);
    }

    public function getQuestions(Request $request)
    {
        //从Exam表随机抽50道题
        $safe = Exam::where('type','安全通识')->inRandomOrder()->take(20)->get()->toArray();
        $yixue = Exam::where('type','医学生物安全')->inRandomOrder()->take(10)->get()->toArray();
        $jixie = Exam::where('type','机械建筑安全')->inRandomOrder()->take(10)->get()->toArray();
        $dianqi = Exam::where('type','电气安全')->inRandomOrder()->take(10)->get()->toArray();
        $answer = array_merge(array_column($safe, 'answer'),array_column($yixue, 'answer'),array_column($jixie, 'answer'),array_column($dianqi, 'answer'));
        Cache::put($request->user_id,$answer);
        $questions = array_merge($this->array_remove_by_key($safe),$this->array_remove_by_key($yixue),$this->array_remove_by_key($jixie),$this->array_remove_by_key($dianqi));

        return response()->json($questions, 200);
    }

    /**
     * 去除答案
     * @param $arr
     * @return array
     */
    private function array_remove_by_key($arr){
        $result = array_map(function($item){
            unset($item['answer']);
            return  $item;
        },$arr);
        return $result;
    }
    public function submitAchievement(Request $request)
    {
        $array = explode(",", $request->achievement);
        $question = array();
        $trueNum = 0;
        for ($i = 0; $i < count($array); $i = $i + 4) {
            if ($array[$i] == true) array_push($question, 'A');
            if ($array[$i + 1] == true) array_push($question, 'B');
            if ($array[$i + 2] == true) array_push($question, 'C');
            if ($array[$i + 3] == true) array_push($question, 'D');
            if ($array[$i] != true && $array[$i + 1] != true && $array[$i + 2] != true && $array[$i + 3] != true) array_push($question, 'null');
        }
//        Log::info($question);
        $cache = Cache::get($request->user_id);
//        Log::info($cache);
        for ($j = 0; $j < count($cache); $j++) {
            if ($cache[$j] == $question[$j]) {
                $trueNum++;
            }
        }
//        Log::info($trueNum);
        return response()->json($trueNum * 2, 200);
    }
}

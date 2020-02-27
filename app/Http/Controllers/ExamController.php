<?php

namespace App\Http\Controllers;

use App\Exam;
use App\ExamManage;
use App\Imports\ExcelImport;

use App\User;
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


    /**
     * 获取试题
     * @author hzj
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuestions(Request $request)
    {
        //从Exam表随机抽50道题
        $examManage = ExamManage::where('unit_id',$request->unit_id)->first();
//        Log::info($examManage);
        $anquantongshi = Exam::where('type','安全通识')->inRandomOrder()->take($examManage->aqts)->get()->toArray();
        $yixueshengwu = Exam::where('type','医学生物安全')->inRandomOrder()->take($examManage->yxsw)->get()->toArray();
        $jixiejianzhu = Exam::where('type','机械建筑安全')->inRandomOrder()->take($examManage->jxjz)->get()->toArray();
        $dianqianquan = Exam::where('type','电气安全')->inRandomOrder()->take($examManage->dqaq)->get()->toArray();
        $huaxuepin = Exam::where('type','化学品安全')->inRandomOrder()->take($examManage->hxp)->get()->toArray();
        $tezhongshebei = Exam::where('type','特种设备安全')->inRandomOrder()->take($examManage->tzsb)->get()->toArray();
        $xiaofanganquan = Exam::where('type','消防安全')->inRandomOrder()->take($examManage->xfaq)->get()->toArray();
        $answer = array_merge(
            array_column($anquantongshi, 'answer'),
            array_column($yixueshengwu, 'answer'),
            array_column($jixiejianzhu, 'answer'),
            array_column($dianqianquan, 'answer'),
            array_column($huaxuepin, 'answer'),
            array_column($tezhongshebei, 'answer'),
            array_column($xiaofanganquan, 'answer')
        );
        Cache::forever($request->user_id,$answer);
        $questions = array_merge(
            $this->array_remove_by_key($anquantongshi),
            $this->array_remove_by_key($yixueshengwu),
            $this->array_remove_by_key($jixiejianzhu),
            $this->array_remove_by_key($dianqianquan),
            $this->array_remove_by_key($huaxuepin),
            $this->array_remove_by_key($xiaofanganquan),
            $this->array_remove_by_key($tezhongshebei)
        );

        return response()->json($questions, 200);
    }

    /**
     * 从结果集去除答案字段
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

    /**
     *
     * 提交答案 返回成绩
     * @author hzj
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
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
        $user =  User::where('user_id',$request->user_id)->first();
        $result = $trueNum*2>$user->exam_result?$trueNum*2:$user->exam_result;
        if ($result>=80){
            $user->exam_result = $result;
            $user->save();
            return response()->json([
                'msg'=>'考试通过',
                'result'=>$trueNum * 2
            ], 200);
        }else{
            $user->exam_result = $result;
            $user->save();
            return response()->json([
                'msg'=>'考试通过',
                'result'=>$trueNum * 2
            ], 200);
        }


    }
}

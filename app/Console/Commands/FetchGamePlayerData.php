<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use App\Models\Match;
use Carbon\Carbon;

class FetchGamePlayerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:fetch-player-game-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取球员及时数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updateMatchs('2019-11-19');
    }

    public function updateMatchs($date){
        $client = new Client();
        $url = 'http://sportsnba.qq.com/match/listByDate?appver=5.3&appvid=5.3&deviceId=5aab4cc468f3416eb5df8f915c3d8fcb&from=app&guid=5aab4cc468f3416eb5df8f915c3d8fcb&height=812&network=WiFi&os=iphone&osvid=12.1&width=375&date='.$date;
        $res = $client->request('GET',$url);
        $data = json_decode($res->getBody()->getContents(),true);
         if (array_key_exists('data',$data)) {
            $data = $data['data'];
            if (array_key_exists('matches',$data)) {
                $matches = $data['matches'];
                foreach ($matches as $match) {
                    if (array_key_exists('matchInfo',$match)) {
                        $match = $match['matchInfo'];
                        $matchType = $match['matchType'];
                        $mid = $match['mid'];
                        $leftId = $match['leftId'];
                        $leftName = $match['leftName'];
                        $leftGoal = $match['leftGoal'];
                        $rightId = $match['rightId'];
                        $rightName = $match['rightName'];
                        $rightGoal = $match['rightGoal'];
                        $startTime = Carbon::parse($match['startTime']);
                        $endTime = Carbon::parse($match['endTime']);
                        if (isset($mid)) {
                            $matchArr = array();
                            $matchArr['type'] = $matchType;
                            $matchArr['mid'] = $mid;
                            $matchArr['left_id'] = $leftId;
                            $matchArr['left_goal'] = $leftGoal;
                            $matchArr['left_name'] = $leftName;
                            $matchArr['right_id'] = $rightId;
                            $matchArr['right_name'] = $rightName;
                            $matchArr['right_goal'] = $rightGoal;
                            $matchArr['start_time'] = $startTime;
                            $matchArr['match_date'] = $startTime;
                            if ($endTime->gte($startTime)) {
                               $matchArr['end_time'] = $endTime;
                            }else{
                               $matchArr['end_time'] = $startTime;
                            }
                            Match::updateOrCreate(array('mid' => $mid), $matchArr);
                        }
                    }
                }
            }
        }
    }
}

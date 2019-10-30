<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use App\Models\Match;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerCareerData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $date = Carbon::parse('today')->toDateString();
        $game = Game::where(array('game_date' => $date))->first();
        if ($game) {
            if ($game->status !== 3) {
                Log::channel('nba')->info('更新游戏数据1: '.$game->id);
                $matchStartTime = Carbon::parse($game->start_time);
                if (Carbon::now()->gte($matchStartTime)) {
                    Log::channel('nba')->info('更新游戏数据2: '.$game->id);
                    $this->updateGame($game);
                 }
            }
        }else{
            Log::channel('nba')->info('游戏不存在: '.$date);
        }
    }


    public function updateGame($game){
        $matches = $this->updateMatchs($game);
        $gameStatusCount = 0;
        foreach ($matches as $match) {
            if ($match->match_period != 0) {
                $this->updatePlayerGameData($match , $match->game_id);
            }
            $gameStatusCount += $match->match_period;
        }
        $status = 1;
        if ($gameStatusCount == 2 * count($matches)) {
            $status = 2;
        }
        $game->status = $status;
        $game->save();
    }

    public function updatePlayerGameData($match , $gameId){
        $matchId = $match->mid;
        $client = new Client();
        $url = 'http://sportsnba.qq.com/match/stat?appver=5.3&appvid=5.3&deviceId=5aab4cc468f3416eb5df8f915c3d8fcb&from=app&guid=5aab4cc468f3416eb5df8f915c3d8fcb&height=812&isVertical=1&network=WiFi&os=iphone&osvid=12.1&tabType=2&width=375&mid='.$matchId;
        $res = $client->request('GET',$url);
        $data = json_decode($res->getBody()->getContents(),true);
        if (array_key_exists('data',$data)) {
            $data = $data['data'];
            if (array_key_exists('stats',$data)) {
                $data = $data['stats'];
                if ( count($data) > 0 && array_key_exists('playerStats',$data[0])) {
                    $data = $data[0]['playerStats'];
                    foreach ($data as $value) {
                        if (array_key_exists('playerId',$value)) {
                            $realData = array();
                            $playerId = $value['playerId'];
                            if (array_key_exists('row',$value)) {
                                $rows = $value['row'];
                                $realData['DPTS'] = count($rows) > 2 ? $rows[2] : 0;
                                $realData['DREB'] = count($rows) > 3 ? $rows[3] : 0;
                                $realData['DAST'] = count($rows) > 4 ? $rows[4] : 0;
                                $realData['DSTL'] = count($rows) > 5 ? $rows[5] : 0;
                                $realData['DBLK'] = count($rows) > 6 ? $rows[6] : 0;
                                $realData['DTO'] = count($rows) > 15 ? $rows[15] : 0;
                            }
                            echo $match->game_id;
                            DB::table('game_player_data')->where(['player_id' => $playerId , 'game_id' => $match->game_id])->update($realData);
                        }
                    }
                }
            }
        }
    }

    public function updateMatchs(Game $game){
        $date = $game->game_date;
        $client = new Client();
        $url = 'http://sportsnba.qq.com/match/listByDate?appver=5.3&appvid=5.3&deviceId=5aab4cc468f3416eb5df8f915c3d8fcb&from=app&guid=5aab4cc468f3416eb5df8f915c3d8fcb&height=812&network=WiFi&os=iphone&osvid=12.1&width=375&date='.$date;
        $res = $client->request('GET',$url);
        $matchModels = array();
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
                        $matchPeriod = $match['matchPeriod'];
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
                            $matchArr['game_id'] = $game->id;
                            $matchArr['match_period'] = $matchPeriod;
                            if ($endTime->gte($startTime)) {
                               $matchArr['end_time'] = $endTime;
                            }else{
                               $matchArr['end_time'] = $startTime;
                            }
                            $matchModels[] = Match::updateOrCreate(array('mid' => $mid), $matchArr);
                        }
                    }
                }
            }
        }
        return $matchModels;
    }
}

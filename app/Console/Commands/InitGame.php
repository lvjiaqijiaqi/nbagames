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

class InitGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:init-game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化游戏';

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
        $date = Carbon::tomorrow()->toDateString();
        $game = Game::where(array('game_date' => $date))->first();
        if (!$game) {
            echo '创建新游戏\n';
            $this->initGame($date);
        }
    }
    public function initGame($date){
        $game = Game::create(array('game_date' => $date));
        $matches = $this->updateMatchs($game);
        $startTime = null;
        foreach ($matches as $match) {
            $this->initPlayers($match->left_id , $match->game_id);
            $this->initPlayers($match->right_id , $match->game_id);
            $gameStartTime = Carbon::parse($match->start_time);
            if ($startTime == null) {
                $startTime = $gameStartTime;
            }else{
               $startTime = $gameStartTime->min($startTime);
            }
        }
        $maxPTS = DB::table('game_player_data')
                ->where('game_id', $game->id)
                ->max('PTS');
        $maxREB = DB::table('game_player_data')
                ->where('game_id', $game->id)
                ->max('REB');
        $maxAST = DB::table('game_player_data')
                ->where('game_id', $game->id)
                ->max('AST');
        $maxSTL = DB::table('game_player_data')
                ->where('game_id', $game->id)
                ->max('STL');
        $maxBLK = DB::table('game_player_data')
                ->where('game_id', $game->id)
                ->max('BLK');
        $maxTO = DB::table('game_player_data')
                ->where('game_id', $game->id)
                ->max('TO');  
        $game->PTS = $maxPTS;
        $game->AST = $maxREB;
        $game->REB = $maxAST;
        $game->STL = $maxSTL;
        $game->BLK = $maxBLK;
        $game->TO = $maxTO;
        $game->start_time = $startTime;
        $game->save();
        /*$gameMax = ['PTS' => $maxPTS,
                    'REB' => $maxREB,
                    'AST' => $maxAST,
                    'STL' => $maxSTL,
                    'BLK' => $maxBLK,
                    'TO' => $maxTO,
                    ];
        print_r($gameMax);*/
    }

    public function initPlayers($teamId , $gameId){
        $players = Player::where('team_id', '=', $teamId)->get();
        $gamePlayers = array();
        foreach ($players as $player) {
            $playerData = PlayerCareerData::where('player_id', '=', $player->player_id)->orderBy('season_year','desc')->where('match_count', '>', 0)->first();
            if($playerData){
                $gamePlayer = array();
                $gamePlayer['player_id'] = $playerData->player_id; 
                $gamePlayer['PTS'] = $playerData->PTS;
                $gamePlayer['REB'] = $playerData->REB;
                $gamePlayer['AST'] = $playerData->AST;
                $gamePlayer['STL'] = $playerData->STL;
                $gamePlayer['BLK'] = $playerData->BLK;
                $gamePlayer['TO'] = $playerData->TO;
                $gamePlayer['TO'] = $playerData->TO;
                $gamePlayer['player_cn_name'] = $player->cn_name;
                $gamePlayer['game_id'] = $gameId;
                $gamePlayer['position_type'] = $player->position_type;
                $gamePlayer['avatar'] = $player->icon;
                $gamePlayers[] = $gamePlayer;
            }else{
                $gamePlayer = array();
                $gamePlayer['player_id'] = $player->player_id; 
                $gamePlayer['PTS'] = 0;
                $gamePlayer['REB'] = 0;
                $gamePlayer['AST'] = 0;
                $gamePlayer['STL'] = 0;
                $gamePlayer['BLK'] = 0;
                $gamePlayer['TO'] = 0;
                $gamePlayer['avatar'] = '';
                $gamePlayer['player_cn_name'] = $player->cn_name;
                $gamePlayer['game_id'] = $gameId;
                $gamePlayer['position_type'] = $player->position_type;
                $gamePlayers[] = $gamePlayer;
            }
        }
        $rs = DB::table('game_player_data')->insert($gamePlayers);
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

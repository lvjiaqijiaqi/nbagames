<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerCareerData;
use App\Models\Enum\PlayerPositionEnum;

class FetchPlayersCarrerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:fetch-player-career-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取球员职业身涯数据';

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
        $this->fetchPlyersCareerData();
    }

    public function fetchPlyersCareerData(){
        $players = Player::all();
        foreach ($players as $player) {
            $this->fetchPlyerCareerData($player->player_id);
        }
    }

    public function fetchPlyerCareerData($playerId){
        echo "start fetch playerDatas with playerId =".$playerId."\n";
        $client = new Client();
        $url = 'http://sportsnba.qq.com/player/stats?appver=5.3&appvid=5.3&deviceId=5aab4cc468f3416eb5df8f915c3d8fcb&from=app&guid=5aab4cc468f3416eb5df8f915c3d8fcb&height=812&network=WiFi&os=iphone&osvid=12.1&tabType=2&width=375&playerId='.$playerId;
        $res = $client->request('GET',$url);
        $data = json_decode($res->getBody()->getContents(),true);
        if (array_key_exists('data',$data)) {
            $data = $data['data'];
        }else{
            echo "complete fetch playerDatas error with playerId =".$playerId."\n";
        }
        $reg = [];
        if (array_key_exists('reg',$data)) {
            $reg = $data['reg']['rows'];
        }
        $playoff = [];
        if (array_key_exists('playoff',$data)) {
            $playoff = $data['playoff']['rows'];
        }
        $seaonDatas = array_merge($reg,$playoff);
        if (count($seaonDatas) > 0) {
            foreach ($seaonDatas as $value) {
            $seasonYear = explode("/",$value[0])[0];
            if($seasonYear != ''){
                $seasonType = $this->getSeasonType($value[2]);
                $PTS = $value[6];
                $REB = $value[7];
                $AST = $value[8];
                $BLK = $value[10];
                $STL = $value[9];
                $TO = $value[23];
                $playerCareerData = array();
                $playerCareerData['player_id'] = $playerId;
                $playerCareerData['season_year'] = $seasonYear;
                $playerCareerData['season_type'] = $seasonType;
                $playerCareerData['PTS'] = $PTS;
                $playerCareerData['REB'] = $REB;
                $playerCareerData['AST'] = $AST;
                $playerCareerData['STL'] = $STL;
                $playerCareerData['BLK'] = $BLK;
                $playerCareerData['TO'] = $TO;
                PlayerCareerData::updateOrCreate(array('player_id' => $playerId , 'season_year' => $seasonYear, 'season_type' => $seasonType), $playerCareerData);
            }
        }
        }
        echo "complete fetch playerDatas with playerId =".$playerId."\n";
    }
    
    public function getSeasonType($typeString){
        if ($typeString == '常规赛') {
            return 1;
        }else if($typeString == '季后赛'){
            return 2;
        }else{
            return 0;
        }
    }
}

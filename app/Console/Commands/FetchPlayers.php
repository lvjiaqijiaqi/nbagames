<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use App\Models\Team;
use App\Models\Player;
use App\Models\Enum\PlayerPositionEnum;

class FetchPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:fetch-players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取球员信息';

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
        $teams = Team::all();
        foreach ($teams as $team) {
            $this->fetchPlyers($team->tid);
        }
    }

    public function fetchPlyers($teamId){
        $client = new Client();
        $url = 'http://sportsnba.qq.com/player/list?appver=5.3&appvid=5.3&deviceId=5aab4cc468f3416eb5df8f915c3d8fcb&from=app&guid=5aab4cc468f3416eb5df8f915c3d8fcb&height=812&network=WiFi&os=iphone&osvid=12.1&width=375&teamId='.$teamId;
        $res = $client->request('GET',$url);
        $data = json_decode($res->getBody()->getContents(),true)['data'];
        $players = array();
        foreach ($data as $value) {
            $player = array();
            $player['player_id'] = $value['id'];
            $player['cn_name'] = $value['cnName'];
            $player['en_name'] = $value['enName'];
            $player['team_id'] = $value['teamId'];
            $player['position'] = $value['position'];
            $player['position_type'] = PlayerPositionEnum::getPlayerPosition($value['position']);
            $player['birth'] = $value['birth'];
            $player['height'] = $value['height'];
            $player['weight'] = $value['weight'];
            $player['icon'] = $value['icon'];
            $player['wage'] = $value['wage'];
            echo $value['id']."\n";
            Player::updateOrCreate(array('player_id' => $player['player_id']),$player);
        }
    }

}

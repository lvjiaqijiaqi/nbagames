<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use App\Models\Team;

class FetchTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:fetch-teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获得球队资料';

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
        $this->fetchTeams();
    }

    public function fetchTeams()
    {
        $client = new Client();
        $res = $client->request('GET', 'http://sportsnba.qq.com/team/list?appver=5.6&appvid=5.6&deviceId=5aab4cc468f3416eb5df8f915c3d8fcb&from=app&guid=5aab4cc468f3416eb5df8f915c3d8fcb&height=812&network=WiFi&os=iphone&osvid=12.4&width=375');
        $data = json_decode($res->getBody()->getContents(),true);
        $west = $data["data"]['west'];
        $east = $data["data"]['east'];
        $teams = array();
        foreach ($west as $value) {
            $team = array();
            $team["tid"] = $value['teamId'];
            $team["name"] = $value['teamName'];
            $team["logo"] = $value['logo'];
            $team["city"] = $value['city'];
            $teams[] = $team;
        }
        foreach ($east as $value) {
            $team = array();
            $team["tid"] = $value['teamId'];
            $team["name"] = $value['teamName'];
            $team["logo"] = $value['logo'];
            $team["city"] = $value['city'];
            $teams[] = $team;
        }
        $qteam = new Team();
        $qteam->addAll($teams);
    } 
}

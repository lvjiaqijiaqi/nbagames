<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\FetchPlayersCarrerData;
use Carbon\Carbon;
use App\Models\Game;
use Illuminate\Support\Facades\Log;

class CloseGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:close-game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结算游戏';

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
        $date = Carbon::now()->toDateString();
        $game = Game::where(array('game_date' => $date))->first();
        if ($game) {
            Log::channel('nba')->info('结算游戏: '.$date);
            $game->status = 3;
            $game->save();
            $this->updatePlayerCareerData();
        }
    }

    public function updatePlayerCareerData()
    {
        $fetchPlayersCarrerData = new FetchPlayersCarrerData();
        $fetchPlayersCarrerData->handle();
    }
}

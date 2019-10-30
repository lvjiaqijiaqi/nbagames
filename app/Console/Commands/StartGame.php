<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Game;

class StartGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nbagame:start-game';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开始游戏';

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
            Log::channel('nba')->info('开始游戏: '.$date);
            $game->status = 1;
            $game->save();
            $this->updatePlayerCareerData();
        }
    }
}

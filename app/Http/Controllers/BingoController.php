<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Game;

/**
 * Class BingoController
 * @package App\Http\Controllers
 */
class BingoController extends Controller
{

    /**
     * @var Game
     */
    protected $Game;

    /**
     * BingoController constructor.
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->Game = $game;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCallingBingoNumber(Request $request): JsonResponse
    {
        try {
            $gameId = $request['game_id'] ?? 1;
            $limitNumbers = Config::get('constants.bingo_limit_numbers');
            do {
                $callingNumber = random_int($limitNumbers['min'], $limitNumbers['max']);
            } while ($this->Game->verifyGameTrack($request['game_id'] ?? 1, $callingNumber));
            $this->Game->calledNumberStore($gameId, $callingNumber);

            return success_api_response('Calling bingo number', $callingNumber);
        } catch (\Exception $e) {
            Log::error('ERROR: BingoController::getCallingBingoNumber ->' . json_encode($e));
            return error_api_response($e->getMessage(), $e->getTraceAsString());
        }
    }

}

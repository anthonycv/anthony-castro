<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class Game
 * @package App\Models
 */
class Game extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'game';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'date_ini',
        'b_rate',
        'i_rate',
        'n_rate',
        'g_rate',
        'o_rate',
        'status'
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var
     */
    public $bRate;
    /**
     * @var
     */
    public $iRate;
    /**
     * @var
     */
    public $nRate;
    /**
     * @var
     */
    public $gRate;
    /**
     * @var
     */
    public $oRate;
    /**
     * @var
     */
    public $gameId;

    /**
     *
     */
    public function setRates(): void
    {
        $game = $this->select('id', 'b_rate', 'i_rate', 'n_rate', 'g_rate', 'o_rate')->first();
        $this->gameId = $game->id;
        foreach (Config::get('constants.bingo_words') as $word) {
            ${$word . 'RateSplit'} = explode('-', $game->{$word . '_rate'});
            $this->{$word . 'Rate'}['min'] = ${$word . 'RateSplit'}[0];
            $this->{$word . 'Rate'}['max'] = ${$word . 'RateSplit'}[1];
        }
    }

    /**
     * @param $gameId
     * @param $number
     * @return bool
     */
    public function verifyGameTrack($gameId, $number): bool
    {
        $game = $this->select('game_track')->where('id', $gameId)->first();
        return in_array($number, json_decode($game->game_track), true);

    }

    /**
     * @param $gameId
     * @param $number
     * @return bool
     */
    public function calledNumberStore($gameId, $number): bool
    {
        $game = $this->find($gameId);
        $gameTrace = json_decode($game->game_track);
        $gameTrace[] = $number;

        return $this->where('id', $gameId)
            ->update(['game_track' => json_encode($gameTrace)]);
    }


    /**
     * @param $gameId
     * @return mixed
     */
    public function getNumberTrace($gameId): array
    {
        return json_decode($this->find($gameId)->game_track, true) ?? [];
    }
}

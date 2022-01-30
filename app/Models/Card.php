<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Card
 * @package App\Models
 */
class Card extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'card';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'game_id',
        'card',
        'status'
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @param $gameId
     * @param $bingoCard
     * @return bool
     */
    public function verifyCardExists($gameId, $bingoCard)
    {
        return $this->where('game_id', $gameId)->where('card', $bingoCard)->count() > 0;
    }

    /**
     * @param $cardId
     * @return mixed
     */
    public function getCard($cardId)
    {
        return $this->find($cardId) ?? false;
    }

    /**
     * @param $gameId
     * @param $bingoCard
     * @return mixed
     */
    public function cardStore($gameId, $bingoCard)
    {
        $this->game_id = $gameId;
        $this->card = $bingoCard;
        $this->save();
        return $this->id;
    }


}

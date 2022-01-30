<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use App\Models\Card;
use Illuminate\Support\Facades\Validator;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
{
    /**
     * @var Game
     */
    protected $Game;
    /**
     * @var Card
     */
    protected $Card;

    /**
     * @var
     */
    private $bingoCard;

    /**
     * CardController constructor.
     * @param Game $game
     * @param Card $card
     */
    public function __construct(Game $game, Card $card)
    {
        $this->Game = $game;
        $this->Card = $card;

        foreach (Config::get('constants.bingo_words') as $word) {
            $this->bingoCard[$word] = [];
        }
    }

    /**
     * @return JsonResponse
     */
    public function cardStore(): JsonResponse
    {
        try {
            do {
                $this->cardGenerate();
            } while ($this->Card->verifyCardExists($this->Game->gameId, json_encode($this->bingoCard)));
            $cardId = $this->Card->cardStore($this->Game->gameId, json_encode($this->bingoCard));
            return success_api_response('Card generated', [
                'card_id' => $cardId,
                'card' => $this->bingoCard
            ]);
        } catch (\Exception $e) {
            Log::error('ERROR: CardController::cardStore ->' . json_encode($e));
            return error_api_response($e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function validateWinnerCard(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                "card_id" => 'required|integer'
            ]);
            if ($validator->fails()) {
                return error_api_response($validator->getMessageBag(), null, 400);
            }
            $card = $this->Card->getCard($request['card_id']) ?? false;
            if (!$card) {
                return error_api_response("the card_id ({$request['card_id']}) does not exist", null, 500);
            }
            $numberTrace = $this->Game->getNumberTrace($card->game_id);
            $card = $this->buildCard($card);
            if($this->validateWinnerCardWithTraceNumber($card,$numberTrace)){
                return success_api_response("The card_id ({$request['card_id']}) is a winner card", true);
            }
            return success_api_response("The card_id ({$request['card_id']}) is a losing card", false);

        } catch (\Exception $e) {
            Log::error('ERROR: CardController::validateWinner ->' . json_encode($e));
            return error_api_response($e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     *
     */
    private function cardGenerate(): void
    {
        $this->Game->setRates();
        foreach (Config::get('constants.bingo_words') as $word) {
            do {
                $this->cardColumnsGenerate($word);
            } while (count($this->bingoCard[$word]) < 5);
        }
        // Set 1 FREE space in the middle
        $this->bingoCard[Config::get('constants.bingo_word_empty')]['2'] = 'none';
    }

    /**
     * @param $word
     * @throws \Exception
     */
    private function cardColumnsGenerate($word): void
    {
        ${$word . 'Number'} = random_int($this->Game->{$word . 'Rate'}['min'], $this->Game->{$word . 'Rate'}['max']);
        if (!in_array(${$word . 'Number'}, $this->bingoCard[$word], true)) {
            $this->bingoCard[$word][] = ${$word . 'Number'};
        }
    }

    /**
     * @param $winnerCard
     * @return array
     */
    private function buildCard($winnerCard): array
    {
        $card = [];
        foreach (json_decode($winnerCard->card) as $column) {
            foreach ($column as $number) {
                $card[] = $number;
            }
        }
        unset($card[array_search('none', $card)]);

        return $card;
    }

    /**
     * @param $card
     * @param $numberTrace
     * @return bool
     */
    private function validateWinnerCardWithTraceNumber($card, $numberTrace): bool
    {
        return count((array_intersect($card, $numberTrace))) === count($card);
    }


}

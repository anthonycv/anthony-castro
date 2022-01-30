<?php

namespace Tests\Unit;

use App\Http\Controllers\BingoController;
use App\Models\Game;
use Tests\TestCase;
use Mockery as m;
use App\Http\Controllers\CardController;
use App\Models\Card;

class KataTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_card_generator()
    {
        $cardController = m::mock('overload:' . CardController::class);
        $cardModel = m::mock('overload:' . Card::class);
        $gameModel = m::mock('overload:' . Game::class);
        $gameModel->id = 1;
        $card = [
            "b" => [1, 11, 14, 13, 3],
            "i" => [23, 27, 28, 18, 16],
            "n" => [40, 37, "none", 33, 44],
            "g" => [54, 49, 50, 59, 56],
            "o" => [61, 63, 70, 68, 71]
        ];
        $cardId = 1;
        $cardController->shouldReceive('cardStore')
            ->andReturn([]);
        $cardController->shouldReceive('cardGenerate')
            ->andReturn($card);
        $cardModel->shouldReceive('cardStore')
            ->with($gameModel->id, json_encode($card))
            ->andReturn($cardId);
        $response = $this->json('POST', '/api/card_generator');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_number_caller()
    {
        $bingoController = m::mock('overload:' . BingoController::class);
        $gameModel = m::mock('overload:' . Game::class);
        $gameModel->id = 1;
        $bingoController->shouldReceive('getCallingBingoNumber')->andReturn([]);

        $gameModel->shouldReceive('verifyGameTrack')
            ->andReturn(true);
        $gameModel->shouldReceive('calledNumberStore')
            ->andReturn($gameModel->id, 25);
        $response = $this->json('POST', '/api/number_caller');
        $response->assertStatus(200);
    }

    /** @test */
    public function test_validate_winner()
    {
        $cardController = m::mock('overload:' . CardController::class);
        $cardModel = m::mock('overload:' . Card::class);
        $gameModel = m::mock('overload:' . Game::class);
        $gameModel->id = 1;
        $card = [
            "b" => [1, 11, 14, 13, 3],
            "i" => [23, 27, 28, 18, 16],
            "n" => [40, 37, 33, 44],
            "g" => [54, 49, 50, 59, 56],
            "o" => [61, 63, 70, 68, 71]
        ];
        $cardController->shouldReceive('validateWinnerCard')
            ->andReturn([]);
        $cardModel->shouldReceive('getCard')
            ->andReturn([]);
        $gameModel->shouldReceive('getNumberTrace')
            ->andReturn($card);
        $cardController->shouldReceive('buildCard')
            ->andReturn($card);
        $cardController->shouldReceive('validateWinnerCardWithTraceNumber')
            ->andReturn(true);
        $gameModel->shouldReceive('calledNumberStore')
            ->andReturn($gameModel->id, 25);
        $response = $this->json('GET', '/api/validate_winner');
        $response->assertStatus(200);
    }
}

<?php

namespace tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use TurnsGame\GameEntity;
use TurnsGame\GameState;
use TurnsGame\GameStatus;
use TurnsGame\PlayerAction;
use TurnsGame\PlayerEntity;
use TurnsGame\Round;

class GameStateTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $gameEntity = Mockery::mock(GameEntity::class);
        $state = new GameState($gameEntity);
        self::assertInstanceOf(GameState::class, $state);
    }

    /**
     * @expectedException TurnsGame\GameStateException
     */
    public function testCanNotExecuteActionIfGameIsNotOngoing()
    {
        $gameEntity = Mockery::mock(GameEntity::class)
            ->shouldReceive('getStatus')
            ->andReturn(GameStatus::finished())
            ->getMock();
        $state = new GameState($gameEntity);
        $action = Mockery::mock(PlayerAction::class);
        $state->executeAction($action);
    }

    /**
     * @expectedException TurnsGame\GameStateException
     */
    public function testCanNotExecuteActionIfPlayerIsNotInGame()
    {
        $playerInGame = Mockery::mock(PlayerEntity::class)
            ->shouldReceive('getId')
            ->andReturn('player_in_game_id')
            ->getMock();

        $anotherPlayerInGame = Mockery::mock(PlayerEntity::class)
            ->shouldReceive('getId')
            ->andReturn('another_player_in_game_id')
            ->getMock();

        $playerNotInGame = Mockery::mock(PlayerEntity::class)
            ->shouldReceive('getId')
            ->andReturn('player_not_in_game_id')
            ->getMock();

        $gameEntity = Mockery::mock(GameEntity::class)
            ->shouldReceive('getStatus')
            ->andReturn(GameStatus::ongoing())
            ->shouldReceive('getPlayers')
            ->andReturn([$playerInGame, $anotherPlayerInGame])
            ->getMock();

        $state = new GameState($gameEntity);

        $action = Mockery::mock(PlayerAction::class)
            ->shouldReceive('getPlayer')
            ->andReturn($playerNotInGame)
            ->mock();

        $state->executeAction($action);
    }

    /**
     * @expectedException TurnsGame\GameStateException
     */
    public function testCanNotExecuteActionIfPlayerExceededNumberOfAllowedActionsThisRound()
    {
        $round = Mockery::mock(Round::class)
            ->shouldReceive('isLessThan')
            ->andReturn(false)
            ->getMock();

        $playerInGame = Mockery::mock(PlayerEntity::class)
            ->shouldReceive('getId')
            ->andReturn('player_in_game_id')
            ->shouldReceive('getLastActionRound')
            ->andReturn($round)
            ->shouldReceive('getNumActions')
            ->andReturn(1)
            ->getMock();

        $anotherPlayerInGame = Mockery::mock(PlayerEntity::class)
            ->shouldReceive('getId')
            ->andReturn('another_player_in_game_id')
            ->getMock();

        $gameEntity = Mockery::mock(GameEntity::class)
            ->shouldReceive('getStatus')
            ->andReturn(GameStatus::ongoing())
            ->shouldReceive('getPlayers')
            ->andReturn([$playerInGame, $anotherPlayerInGame])
            ->shouldReceive('getNumActions')
            ->andReturn(2)
            ->shouldReceive('getRound')
            ->andReturn($round)
            ->getMock();

        $state = new GameState($gameEntity);

        $action = Mockery::mock(PlayerAction::class)
            ->shouldReceive('getPlayer')
            ->andReturn($playerInGame)
            ->mock();

        $state->executeAction($action);
    }
}

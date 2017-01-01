<?php

namespace TurnsGame\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use TurnsGame\ActionResponse;
use TurnsGame\GameEntity;
use TurnsGame\GameState;
use TurnsGame\GameStatus;
use TurnsGame\PlayerAction;
use TurnsGame\PlayerEntity;

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
}

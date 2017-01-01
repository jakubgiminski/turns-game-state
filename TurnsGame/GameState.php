<?php

namespace TurnsGame;

final class GameState
{
    private $gameEntity;

    public function __construct(GameEntity $gameEntity)
    {
        $this->gameEntity = $gameEntity;
    }

    public function executeAction(PlayerAction $action): ActionResponse
    {
        $this->validateGameStatus();
        $this->validatePlayerPermission($action->getPlayer());

        try {
            $actionResponse = $action->execute();
            $this->syncPlayer($actionResponse->getPlayer());
        } catch (\Exception $e) {
            throw new GameStateException($e->getMessage());
        }

        return $actionResponse;
    }

    public function getGame(): GameEntity
    {
        return $this->gameEntity;
    }

    private function validateGameStatus()
    {
        if (!GameStatus::ongoing()->isEqual($this->gameEntity->getStatus())) {
            throw new GameStateException('Invalid status');
        }
    }

    private function validatePlayerPermission(PlayerEntity $player)
    {
        if (!$this->isPlayerInGame($player)) {
            throw new GameStateException('Invalid player');
        }

        // Is it Player's first action in this round?
        if ($player->getLastActionRound()->isLessThan($this->gameEntity->getRound())) {
            return;
        }

        if ($player->getNumActions() >= $this->gameEntity->getNumActions()) {
            throw new GameStateException('Player exceeded all actions for this round');
        }
    }

    private function isPlayerInGame(PlayerEntity $player): bool
    {
        foreach ($this->gameEntity->getPlayers() as $gamePlayer) {
            if ($gamePlayer->getId() === $player->getId()) {
                return true;
            }
        }
        return false;
    }

    private function syncPlayer(PlayerEntity $player)
    {
        foreach ($this->gameEntity->getPlayers() as &$gamePlayer) {
            if ($gamePlayer->getId() === $player->getId()) {
                $gamePlayer = $player;
                return;
            }
        }
        throw new \InvalidArgumentException('Player unknown to the game');
    }
}

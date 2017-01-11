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
            $player = $this->logPlayerAction($actionResponse->getPlayer());
            $this->updateGameWithPlayer($player);
            $this->updateState();
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

    private function updateGameWithPlayer(PlayerEntity $player): PlayerEntity
    {
        // Is it Player's first action in this round?
        if ($player->getLastActionRound()->isLessThan($this->gameEntity->getRound())) {
            return $player
                ->setLastActionRound($this->gameEntity->getRound())
                ->setNumActions(1);
        }

        // todo update game entity

        $numActions = $player->getNumActions() + 1;
        return $player->setNumActions($numActions);
    }

    private function updateState()
    {
        foreach ($this->gameEntity->getPlayers() as $player) {
            if (
                $player->getLastActionRound()->isLessThan($this->gameEntity->getRound())
                || $player->getNumActions() < $this->gameEntity->getNumActions()
            ) {
                return;
            }
        }

        $currentRound = $this->gameEntity->getRound();

        if ($this->gameEntity->getNumRounds() === $currentRound + 1) {
            $this->gameEntity->setStatus(GameStatus::finished());
            return;
        }

        $round = $this->gameEntity->getRound() + 1;
        $this->gameEntity->setRound($round);
    }
}

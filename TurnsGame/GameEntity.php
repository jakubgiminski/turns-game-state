<?php

namespace TurnsGame;

interface GameEntity
{
    public function setId(string $id): self;
    public function getId(): string;

    public function setStatus(GameStatus $status): self;
    public function getStatus(): GameStatus;

    public function setRound(Round $round): self;
    public function getRound(): Round;

    public function setNumRounds(int $numRounds): self;
    public function getNumRounds(): int;

    public function setNumActions(int $numActions): self;
    public function getNumActions(): int;

    public function setMinNumPlayers(int $minNumPlayers): self;
    public function getMinNumPlayers(): int;

    public function setMaxNumPlayers(int $maxNumPlayers): self;
    public function getMaxNumPlayers(): int;

    public function setPlayers(PlayerEntity ...$players): self;
    public function getPlayers(): array;
}

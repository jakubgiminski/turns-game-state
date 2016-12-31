<?php

namespace TurnsGame;

interface PlayerEntity
{
    public function setId(string $id): self;
    public function getId(): string;

    public function setName(string $name): self;
    public function getName(): string;

    public function setLastActionRound(Round $round): self;
    public function getLastActionRound(): Round;

    public function setNumActions(int $numActions): self;
    public function getNumActions(): int;
}

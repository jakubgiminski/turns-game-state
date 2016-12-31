<?php

namespace TurnsGame;

interface PlayerAction
{
    public function getPlayer(): PlayerEntity;
    public function execute(): ActionResponse;
}

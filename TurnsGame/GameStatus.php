<?php

namespace TurnsGame;

class GameStatus extends ValueObject\StringValue
{
    protected $allowedValues = ['unstarted', 'ongoing', 'finished'];

    public static function ongoing()
    {
        return new self('ongoing');
    }
}

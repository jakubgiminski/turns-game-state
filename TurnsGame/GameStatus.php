<?php

namespace TurnsGame;

use ValueObject\StringValue\StringValue;

class GameStatus extends StringValue
{
    protected $allowedValues = ['unstarted', 'ongoing', 'finished'];

    public static function ongoing()
    {
        return new self('ongoing');
    }
}

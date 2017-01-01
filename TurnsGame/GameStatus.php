<?php

namespace TurnsGame;

use ValueObject\StringValue\StringValue;

class GameStatus extends StringValue
{
    protected static $allowedValues = ['unstarted', 'ongoing', 'finished'];

    public static function ongoing()
    {
        return new self('ongoing');
    }

    public static function unstarted()
    {
        return new self('unstarted');
    }

    public static function finished()
    {
        return new self('finished');
    }
}

# TurnsGameState

Library for managing state of a game, in which players take turns.

### Domain Logic Assumptions
* There can be any number of players in the game.
* There is a configurable number of rounds in the game. Every player takes part in each one.
* Each player can execute a configurable number of actions in a single round.
* Round ends when every player executed maximum number of actions per round.
* Game ends after last round has ended.

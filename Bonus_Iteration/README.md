### Bonus Iteration

This is my solution on [Bonus Iteration](https://github.com/PuttingTheDnDInTDD/EverCraft-Kata#bonus-iteration---battle-grid) of EverCraft-Kata

#### Feature: Grid-based map
* Characters move on a grid-based map

#### Feature: Initiative
* Characters roll a 20-dice to define the initiative. (The higher the dice, the sooner the player plays)
* Each player can either move or attack on each round, not both

#### Feature: Speed
* All races can move up to 20 squares per round
* Dwarves and Halflings can move up to 15 squares per round

#### Feature: Terrain
* Each square has a specific terrain
* Terrain can be 
    * higher (gives attacker +1 to attack roll)
    * lower (gives attacker -1 to attack roll)
    * difficult (character moves with half speed)

#### Feature: Weapon Range
* All non-missile weapons have range 1 square
* Bows have range 2 - 30 square (it can't hit a target on an adjacent square) 


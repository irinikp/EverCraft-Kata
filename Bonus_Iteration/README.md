### Bonus Iteration

This is my solution on [Bonus Iteration](https://github.com/PuttingTheDnDInTDD/EverCraft-Kata#bonus-iteration---battle-grid) of EverCraft-Kata

#### Feature: Grid-based map
* Create a grid-based map

#### Feature: Terrain
* Each square has a specific terrain
* Terrain can be 
    * normal
    * higher
    * lower
    * difficult

#### Feature: Movement/Speed
* All races have movement speed 20 squares per round
* Dwarves and Halflings have movement speed 15 squares per round
* Characters can move on the map (not diagonally)

#### Feature: Terrain affects the battle
* Higher terrain gives attacker +1 to attack roll
* Lower terrain gives attacker -1 to attack roll
* Character on difficult terrain moves with half speed

#### Feature: Initiative
* Characters roll a 20-dice to define the initiative. (The higher the dice, the sooner the player plays)
* Each player can either move or attack on each round, not both

#### Feature: Weapon Range
* All non-missile weapons have range 1 square
* Bows have range 2 - 30 square (it can't hit a target on an adjacent square) 


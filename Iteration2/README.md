### Iteration 2 - Classes
This is my solution on [Iteration 2](https://github.com/PuttingTheDnDInTDD/EverCraft-Kata/blob/master/README.markdown#iteration-2---classes) of EverCraft-Kata

Classes that a character can have.

#### Feature: Characters Have Classes

> As a player I want a character to have a class that customizes its capabilities so that I can play more interesting characters

##### Ideas

- changes in hit points
- changes in attack and damage
- increased critical range or damage
- bonuses/penalties versus other classes
- special abilities
- alignment limitations

##### Samples

> As a player I want to play a Fighter so that I can kick ass and take names

- attacks roll is increased by 1 for every level instead of every other level
- has 10 hit points per level instead of 5

> As a player I want to play a Rogue so that I can defeat my enemies with finesse

- does triple damage on critical hits
- ignores an opponents Dexterity modifier (if positive) to Armor Class when attacking
- adds Dexterity modifier to attacks instead of Strength
- cannot have Good alignment

> As a player I want to play a Monk so that I can enjoy being an Asian martial-arts archetype in a Medieval European setting

- has 6 hit point per level instead of 5
- does 3 points of damage instead of 1 when successfully attacking
- adds Wisdom modifier (if positive) to Armor Class in addition to Dexterity
- attack roll is increased by 1 every 2nd and 3rd level

> As a player I want to play a Paladin so that I can smite evil, write wrongs, and be a self-righteous jerk

- has 8 hit points per level instead of 5
- +2 to attack and damage when attacking Evil characters
- does triple damage when critting on an Evil character (i.e. add the +2 bonus for a regular attack, and then triple that)
- attacks roll is increased by 1 for every level instead of every other level
- can only have Good alignment

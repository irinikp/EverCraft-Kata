# EverCraft-Kata

This is my solution on [EverCart-Kata](https://github.com/PuttingTheDnDInTDD/EverCraft-Kata)
Each folder contains the solution after I finished each Iteration. This way one can see my train of thought while I 
developed each phase, and in which phases I decided to restructure.

[Iteration 1 - Core](Iteration1/)

[Iteration 2 - Classes](Iteration2/)

[Iteration 3 - Races](Iteration3/)

[Iteration 4 - Weapons, Armor & Items](Iteration4/)

[Bonus Iteration - Battle Grid](Bonus_Iteration/)

#### My train of thought

All code is written in PHP, therefore Single Inheritance was used. 
Multiple Inheritance through Interfaces was not used because it would end up in code duplication 
that would be resolved by the use of Traits, which I don't enjoy :)
I created the following hierarchy:

```console
CoreBuild
├── Race
│   ├── Human
│   ├── Dwarf
│   ├── Elf
│   ├── Orc
│   └── Halfling
├── SocialClass
│   ├── Fighter
│   ├── Monk
│   ├── Priest
│   ├── Paladin
│   └── Rogue
└── Item
    ├── Weapon
    │    └── Longsword
    │    │   └── Elven
    │    ├── NunChucks
    │    └── Waraxe
    ├── Armor
    │    └── ChainMail
    │    │   └── Elven
    │    ├── Leather
    │    ├── LeatherOfDamageReduction
    │    └── Plate
    ├── Shield
    ├── AmuletOfTheHeavens
    ├── BeltOfGiantStrength
    └── RingOfProtection
```
Whenever I needed to compute a function that is set by all the above fields, I used `\EverCraft\Character\callFunctionTree()`
and `\EverCraft\CoreStructureCaller`
to call all functions through that tree. The basic functions are set on `CoreBuild` and the items below it
overwrite it only when it's necessary

All attributes of a Character are set on `\EverCraft\Character`

`\EverCraft\Battle\CombatAction` implements all functions necessary on a single combat action 
In our case, the only combat actions set are 'move' and 'attack'. 

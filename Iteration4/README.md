### Iteration 4 - Weapons, Armor & Items

This is my solution on [Iteration 4](https://github.com/PuttingTheDnDInTDD/EverCraft-Kata/blob/master/README.markdown#iteration-4---weapons-armor--items) of EverCraft-Kata

Items that enhance a characters capabilities.

#### Feature: Weapons

> As a character I want to be able to wield a single weapon so that I can achieve victory through superior firepower

- character can wield only one weapon

##### Ideas

- basic weapons that improve damage (dagger)
- basic weapons that improve to attacks (+1 sword)
- magic weapons with special properties (knife of ogre slaying)
- weapons that certain classes or races can or cannot wield

##### Samples

> As a character I want to be able to wield a longsword so that I can look cool

- does 5 points of damage

> As a character I want to be able to wield a +2 waraxe that so that I can *be* cool

- does 6 points of damage
- +2 to attack
- +2 to damage
- triple damage on a critical (quadruple for a Rogue)

> As an elf I want to be able to wield a elven longsword that so I can stick it to that orc with the waraxe

- does 5 points of damage
- +1 to attack and damage
- +2 to attack and damage when wielded by an elf *or* against an orc
- +5 to attack and damage when wielded by an elf *and* against orc

> As a monk I want nun chucks that work with my martial arts so that I can kick ass like Chuck Norris

- does 6 points of damage
- when used by a non-monk there is a -4 penalty to attack

#### Feature: Armor

> As a character I want to be able to don armor and shield so that I can protect myself from attack

- character can only don one shield and wear one suit of armor

##### Ideas

- basic armor that improves armor class (plate)
- magic armor that has special properties
- armor and shields that are or are not usable by certain races or classes

##### Samples

> As a character I want to the be able to wear leather armor so that I can soften attacks against me

- +2 to Armor Class

> As a fighter (or dwarf) I want to be able to wear plate armor so that I can ignore the blows of my enemies

- +8 to Armor Class
- can only be worn by fighters (of any race) and dwarves (of any class)

> As a character I want to the be able to wear magical leather armor of damage reduction so that I can soften attacks against me

- +2 to Armor Class
- -2 to all damage received

> As an elf I want to be able to wear elven chain mail so that I can fit in with all the other elves

- +5 to Armor Class
- +8 to Armor Class if worn by an elf
- +1 to attack if worn by an elf

> As a fighter I want to be able to hold a shield in my off-hand so that I can block incoming blows

- +3 to Armor Class
- -4 to attack
- -2 to attack if worn by a fighter

#### Feature: Items

> As a character I want to be able to have items that enhance my capabilities so that I can be more bad-ass

- can carry multiple items

##### Ideas
- items that improve combat with types of weapons
- items that improve stats against enemies with a certain alignment or race
- items that improve abilities

##### Samples

> As a character I want to be able to wear a ring of protection so that I can be protected from attack

  - adds +2 to armor class

> As a character I want to be able to wear a belt of giant strength so that I can be even stronger in combat

  - add +4 to Strength Score

> As a character I want to be able to wear an amulet of the heavens so that I can strike down evil with holy power

  - +1 to attack against Neutral enemies
  - +2 to attack against Evil enemies
  - double above bonuses if worn by a paladin

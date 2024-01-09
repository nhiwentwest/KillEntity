# KillEntity Plugin for PocketMine

## Overview

The KillEntity plugin is designed to enhance gameplay on PocketMine servers by providing a dynamic reward system for players who defeat specific types of entities. 

## Features

- **Entity-Specific Rewards:** Define unique monetary rewards for each supported entity type, encouraging players to engage with a variety of in-game challenges.

- **Configurability:** Easily customize the plugin settings using the provided configuration file to tailor the experience to your server's unique dynamics.


## Configuration Example

```yaml
# %money = amount money
animals:
  - Zombie: 10
  - Villager: 20
  - Pig: 15

disable_worlds:
  - swevagin
```

## Note

Make sure to have BedrockEconomy installed for optimal functionality.

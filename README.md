# KillEntity Plugin for PocketMine

## Overview

KillEntity provides a monetary reward to players who successfully defeat specific types of entities. Moreover, you can set up the percentage of money that players will lose if they die during combat!

## Features

- **Configurability:** Easily customize the plugin settings using the provided configuration file to tailor the experience to your server's unique dynamics.
- **Permissions:** killentity.plugin


## Configuration Example

```yaml
#format => name_entity: money
animals:
- Zombie: 10
- Villager: 20
# 1 for sending pop-ups
# 2 for sending messages
# 3 to turn off message notifications
message: 1
percent: 50
# enter the name world that you want to be activated
worlds:
- world
```

[![](https://poggit.pmmp.io/shield.state/KillEntity)](https://poggit.pmmp.io/p/KillEntity)



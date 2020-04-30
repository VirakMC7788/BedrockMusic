# BedrockMusic
Adds the ability to play music on PocketMine-MP

## How to use
This plugin works with .nbs files by [Minecraft Note Block Studio](https://www.stuffbydavid.com/mcnbs).

You can find a tutorial for how this works on my [YouTube Channel](https://www.youtube.com/channel/UCZzBs3wwBPbP4PNObZJK06g?).

Important: The API for this has been created by [BrokenItZAndrei100](https://github.com/BrokenItZAndrei100/ZMusicBox/). Please go check it out.

## Add songs
If you want to add songs, just drag them into the `plugin_data/BedrockMusic/` folder. They will automatically register while loading the plugin -> you have to restart after adding new songs
If you don't want to create your own songs, here is a [link](https://github.com/Ruinscraft/powder-resources/tree/master/songs/songs) to finished songs. (These are not mine)

## Configuration
You can change that the song will only be played to a player if he is in a certain radius to a position in`plugin_data/BedrockMusic/config.yml`.
Radius and position are changeable via command in-game.

```yml
# if set to true it will only play the music to a player when he is in a specific radius to the position
onlyradius: false
radius: 12
position: '0:0:0'
```

## Commands
| Default command | Parameter | Description | Permission |
| :-----: | :--------: | :---------: | :----------: |
| /bedrockmusic | [position / radius / ---] | Plays music | bedrockmusic.command |

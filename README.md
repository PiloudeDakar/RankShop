<h1 align="center"> RankShop</h1>

<p align="center"><img src="https://github.com/PiloudeDakar/RankShop/blob/87aaf913b6a5e3bfe589f649df3945164f72f6d8/icon.png" width="200"></p>

This plugin permits the creation of a custom rank shop. In the shop, you can add different ranks (from the plugin PurePerms) that players can buy with the money(from EconomyAPI).


<h2>Commands</h2>

`/up` : open the RankShop


<h2>Permissions</h2

`up.RankShop` : use the `/up` command (default: op)


<h2>Config</h2>

```yml
#title of the main form
title: RankShop by PiloudeDakar

#content of the main form
content: Choose a rank to buy

#subCategory for the rank
exampleRank:
  
  #title of the rank form
  title: Example rank
  
  #content of the rank form
  content: Buy the example rank
  
  #exact name of the rank, with the colours (§.)
  name: §3ExampleRank
  
  #price of the rank
  price: 100000```
  You can create infinite ranks like that. All of the parameters are needed.

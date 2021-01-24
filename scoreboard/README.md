# Geek Off scoring system - Python Scoreboard

## Purpose

This is the Python scoreboard for the Geek Off. It connects to the database and subscribes to an event listener to automatically get changes to the scores from the database.

This was created because of complaints on the past scoreboard being a web page that refreshed every 30 seconds.

This runs on a separate Raspberry Pi Zero W that is connected via wireless to the Geek-O-Matic master control unit.

### How it works

The scoreboard will automatically change based on the round of play loaded in the database.

Fonts used in the scoreboard are not included for copyright reasons. These fonts can be found as follows:

| Font name | URL | Use |
|--|--|--|
| comicscript.ttf | Included with Mac OS Motion/FCPX | Round 3 names |
| fast-money-three.ttf | https://fontstruct.com/fontstructions/show/1181116/fast_money_three | Round 1 board |
| mayqueen.ttf | https://www.dafont.com/may-queen.font | Round 3 names |
| posterboard.ttf | Included with Mac OS Motion/FCPX | Round 3 names |
| souvenirsemibold.ttf | Commercial font | Round 2 scoreboard |
| sportstype.ttf | http://tpirepguide.com/qwizx/tpirfonts/sportstype.zip | Round 3 scores |


Images used in the scoreboard are named as follows:
- jep16star.jpg: Jeopardy scoreboard background
- pyramid_logo.png: Pyramid logo used for second round scoreboard

## Libraries

This requires the Psycopg2 and Pygame libraries.

## Running this yourself

Change the database connection string to your Postgres database.

## Disclaimer

The work presented here does not reflect upon my employer and is not an official product of my employer.

There are probably better ways to code this, but I'm not a Python programmer by trade. As usual with code...if it works, run with it.

## Copyright

The code is Copyright © 2015-2019 Kevin Trinkle and is made available under the GPL v3 license.
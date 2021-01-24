# Geek Off scoring system - Score-O-Matic

## Purpose

This is the scoreboard UI for the Geek Off. It is written in PHP and is run as a web page to allow creation of teams and scoring.

### How it works

Each event has to be a unique ID - those are manually created in the database and not created through the UI. In the UI, you will need to set up the current event that is in play by going to `./setevent`.

### Setting up teams

Each team consists of two players, and by going to the team management screen `./team_update`, you can enter in the players, workgroups, and how much money was raised.

Bonus points are awarded for how much money is raised, and this is controlled in a database view.

### Loading questions

Questions are loaded manually in the database for each event. Each question requires the event ID, round, question number and points value.

For rounds, the question ID is assigned to a particular round. How we run them is the following:

| Round | Question IDs | Notes |
|--|--|--|
| 1 | 101-117 | 15 regular questions. 116, 117 are tiebreakers. |
| 2 | 201-207 | 6 regular categories. 207 is a tiebreaker. |
| 3 | 301-344 | 20 questions, with the second digit being category. |
| 4 | 350 | 1 question, no fixed point value. |

### Starting Game Play

To start game play, go to one of the following pages:

- For regular employee game play, go to `./emp_1`.
- For media day game play, go to `./media_1`.

The game flows are as follows:

| Round number | Employee | Media |
|--|--|--|
| 1 | Written Answers | Written Answers |
| 2 | Pyramid | |
| 3 | Jeopardy | |
| 4 | Final Jeopardy | Pyramid |

The Media Day logic was never used.

The UI is fairly self-explanatory. When each round is finalized, the top teams are written to the database and the active round is changed. The active round is used by the scoreboard.

In the Jeopardy round, for the Daily Double question, the point value being wagered must be entered. It's rather sophisticated - if the team got it wrong, just put in a negative value. The same rules apply for Final Jeopardy.

### Scoreboard

There is a web scoreboard included at `./score_1`. This will automatically reroute to `./score_2` and `./score_3` based on the current active round.

Fonts used in the scoreboard are not included for copyright reasons. These fonts can be found as follows:

| Font name | URL | Use |
|--|--|--|
| bladrmf.ttf | http://www.fontsaddict.com/font/blade-runner-movie-font.html | Score-o-matic title |
| comicscript.ttf | Included with Mac OS Motion/FCPX | Round 3 names |
| eggcrate.ttf | http://tpirepguide.com/qwizx/tpirfonts/eggcrate.zip | Round 1 board |
| garamondbknrw.ttf | Commercial font | (can't recall offhand) |
| mayqueen.ttf | https://www.dafont.com/may-queen.font | Round 3 names |
| posterboard.ttf | Included with Mac OS Motion/FCPX | Round 3 names |
| souvenirsemibold.ttf | Commercial font | Round 2 scoreboard |
| sportstype.ttf | http://tpirepguide.com/qwizx/tpirfonts/sportstype.zip | Round 3 scores |
| stentiga.ttf | https://www.dafont.com/stentiga.font | Team Info event title |


Images used in the scoreboard are named as follows:
- jep16star.jpg: Jeopardy scoreboard background
- pyramid_logo.png: Pyramid logo used for second round scoreboard

## Libraries

This is written in PHP in the CodeIgniter 3 Framework: https://github.com/bcit-ci/CodeIgniter. Uikit elements are used to generate the UI: https://getuikit.com

## Running this yourself

You will need to create an out of the box installation of CodeIgniter PHP framework. Then install the folders in this repo into the appropriate locations of the CodeIgniter base install.

Install uikit into the ./uikit folder and add scoreomatic.css to ./uikit/css. Include font files in that folder.

You will need to set your database connection in `./config/database.php`.

There is no built in authentication. I ran this using basic authentication on the web site. Today, I would suggest something more secure.

## The future

This was originally written in PHP/MySql due to the limitations of my web host, and run as a password protected application behind basic authentication on that shared hosting.

I no longer do much in PHP as my day job has moved me toward an Angular/.NET C# framework. As such, this functionality will eventually be rewritten in Angular/C# to replace this code, and moved to Azure for when the next event occurs.

## Disclaimer

The work presented here does not reflect upon my employer and is not an official product of my employer.

## Copyright

The code is Copyright © 2015-2019 Kevin Trinkle and is made available under the GPL v3 license.
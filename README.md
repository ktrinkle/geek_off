# Geek Off scoring system

## Purpose
This repository contains the code used to support a fundraising event at my company. The annual event consists of three game show style rounds:

1. Written questions and answers
2. Pyramid: 60 seconds for one team member to guess categories based on clues from the other team member.
3. Jeopardy: Classic question and answer game.

## Hardware architecture

### Master Control (MCP)

The primary control unit is a Raspberry Pi, set up to run as a local wireless network. This Pi is not connected to the Internet. It runs the following items:

- Nginx web server
- Postgres database
- Python

Each response unit is connected to the Raspberry Pi via USB. The response units are Arduino units, connected to two response buttons via RCA cabling. Each button is very simple and connects to the RCA jack to close the circuit.

### Scoreboard

The scoreboard is run in Python on a Raspberry Pi. It connects to the MCP via WiFi.

### Control computer

The control computer can be anything, as long as it connects to the Raspberry Pi via WiFi. 

## Code deployment units

| Deployment unit | Language | Runs on | Comments |
|--|--|--|--|
| Database | Postgresql | Raspberry Pi | |
| Geek-o-matic | Python | Raspberry Pi | |
| Response Units | Arduino | Arduino | |
| Score-o-matic | PHP/NGINX | Raspberry Pi | Web site |
| Scoreboard | Python | Raspberry Pi | |


## Disclaimer

Certain items are not uploaded to this repo for copyright reasons. Links or comments are provided in the README.md for each directory to explain how to run this code.

The work presented here does not reflect upon my employer and is not an official product of my employer.

## Copyright

The code is Copyright © 2014-2020 Kevin Trinkle and is made available under the GPL v3 license.
# Geek Off scoring system - response units

## Purpose

This is the code running on each Arduino that connects to the Master Control Raspberry Pi. Each one is customized for a particular "color" - the color reflects a constant value from the serial of the board that ties to a team.

On the Arduino, pins 10 and 11 are connected to the RCA jacks for the buttons, and pin 12 connects to the LED on top of the display unit to show that a team has buzzed in.

The master control will send back a four digit score to the Arduino, which displays on the four segment display. This code is not the most optimal, but it's designed to fit in the smallest serial packet possible. Pins 2 and 3 on the Arduino are used to control the seven segment display.

## Libraries

This requires the SevenSegmentTM1637 library, found at:
https://github.com/bremme/arduino-tm1637

## Credits

This code is very loosely based on the Quiz-O-Tron 3000 by Roy Rabey.
https://www.instructables.com/Quiz-O-Tron-3000-Arduino-quiz-contestant-lockout-/

## Disclaimer

The work presented here does not reflect upon my employer and is not an official product of my employer.

## Copyright

The code is Copyright © 2014-2020 Kevin Trinkle and is made available under the GPL v3 license.
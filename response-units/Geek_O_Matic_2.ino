/*
Geek-O-Matic ][ individual units MCP
version 1.01 26JUL19 KT

Loosely based on Quiz-O-Tron 3000 MCP
By Roy Rabey
Version 1.0 6-DEC-2010
*/

// include the SevenSegmentTM1637 library
#include "SevenSegmentTM1637.h"
#include "SevenSegmentExtended.h"


//Generic for all units
int inputPins[2] = {11, 10}; // The numbers of the switch pins.
int outputPin = 12; // The numbers of the case LED pins.

/*Change color constants here
 * Red = R/S/D
 * Green = G/H/E
 * Blue = B/C/F
 * Yellow = Y/X/A - not built
 */

char respVal = 'R';
char winVal = 'S';
char scoreVal = 'D';
char unlockVal = 'U';
char lockVal = 'L';
boolean openQ = false;

String scoreRtn = "D   0";

// Some variables to control processing
int maxPins = 2; // Max number of pin sets
unsigned long WinDelayTime = 1000; // One second
int waitSec = 5; //waiting seconds

char receivedChar;
boolean newData = false;
String text;

//lcd stuff
String LcdDisp = "";
#define PIN_CLK 2//pins definitions for TM1637 and can be changed to other ports 
#define PIN_DIO 3
SevenSegmentExtended      display(PIN_CLK, PIN_DIO);


void winner(); // Function definition.
void checkBtn(); //Check button loop def
void recvOneChar(); //get char from serial
void displayScore(); //display score on LCD

//
// Begin processing
//
void setup() {
/*
setup() is performed once when the Arduino is powered up or reset.
*/

  // Initialize the LED pins.
  // This tells the Arduino how the pins will be used.
  Serial.begin(115200);
  for(int p=0; p < maxPins; p++) {
    pinMode(inputPins[p], INPUT_PULLUP); // Make this an input pin.
  }
  pinMode(outputPin, OUTPUT); // Make this an output pin.
  display.begin();            // initializes the display
  display.setBacklight(100);  // set the brightness to 100 %

}


void loop(){
/*
The loop() function is executed after the setup() function completes.
As the name implies the loop() function loops forever or until the Arduino is reset.
*/
  recvOneChar();

  if(newData) {
    if (receivedChar == unlockVal) {
        //Serial.print("UnlockVal\n");
        openQ = true;
    } else if (receivedChar == winVal) {
        //Serial.print("winner\n");
        winner();
        //Serial.print("WinVal\n");
        openQ = false;
    } else if (receivedChar == lockVal) {
        //Serial.print("LockVal\n");
        openQ = false;
    } else if (receivedChar == scoreVal) {
        scoreRtn = "";
        scoreRtn = Serial.readStringUntil('\n');
        displayScore();
    } 
  }

  if (openQ) {
    checkBtn();
  }
  newData = false;

}

void recvOneChar() {
  if (Serial.available() > 0) {
    receivedChar = Serial.read();
    Serial.print(receivedChar);
    newData = true;
    }
  }

void checkBtn() {
  if (digitalRead(11) == LOW or digitalRead(10) == LOW) {
     Serial.write("R");
  }
 }

void winner(){
   
   // Set the output pin HIGH to send power to the button's LED circuit.

   digitalWrite(outputPin, HIGH); // Turn the LEDs on

   //Start the countdown. Kludge-y but who cares
   for(int i=waitSec; i>0; i--) {
    text = "   " + String(i);
    display.clear();
    display.print(text);
    // Wait WinDelayTime milliseconds
    delay(WinDelayTime);
   }

   // Set the output pin LOW to kill power to the button's LED circuit.
   digitalWrite(outputPin, LOW); // Turn the LEDs off
   displayScore();
}

void displayScore(void){
   Serial.print(scoreRtn);
   LcdDisp = scoreRtn.substring(0,4);
   display.clear();
   display.print(LcdDisp);
}

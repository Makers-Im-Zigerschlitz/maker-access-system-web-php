/*
  Maker Access System (MAS)
  By Makers im Zigerschlitz
  http://zigerschlitzmakers.ch
  Github: https://github.com/Makers-Im-Zigerschlitz/maker-access-system
*/

#include <SPI.h>
#include <HardwareSerial.h>
#include <ArduinoJson.h>
#include <WiFi.h>
#include <Wire.h>  
#include "SSD1306.h"
// ****************************************************************************  SETTINGS BLOCK  ****************************************************************************
//Define your WiFi credentials and host running MAS as well as your site-wide salt
const char* ssid     = "SSID";
const char* password = "PASS";
const char* host = "HOST";
const String salt = "SALT";
const String subfolder = "SUBFOLDER"; //If MAS is located in subfolder use "/subfolder"

//Define ID of this device - corresponds to ID in MAS admin panel
const int deviceID = 9;

//Define Pins of RGB LED or individuale LEDs
const int pinRed = 32;
const int pinGreen = 35;
const int pinBlue = 34;

//Define Pin to control Relay
const int pinRelay = 25;

//Define pins for connection to RDM6300 RFID reader
const int pinRFID_toRx = 5;
const int pinRFID_toTx = 4;

//Define timing constants
const int TagLockout = 15000;      // Time after which the device checks if same RFID tag is still present (in ms)
// ****************************************************************************  SETTINGS BLOCK  ****************************************************************************

HardwareSerial RFID(1);
SSD1306 display(0x3c, 21, 22);

//Varibles for active session
int deviceActive = 0;
String activeSession = "";

//Tag id associated variables
String InputStringA = "";
String LastTagRx = "";

//Tag timing associated variabels
unsigned long LastTag = 0;
unsigned long LastCheck = 0;
unsigned long startMillis = 0;

//Buffer for RFID
const int BUFFER_SIZE = 14; // RFID DATA FRAME FORMAT: 1byte head (value: 2), 10byte data (2byte version + 8byte tag), 2byte checksum, 1byte tail (value: 3)
const int DATA_SIZE = 10; // 10byte data (2byte version + 8byte tag)
const int DATA_VERSION_SIZE = 2; // 2byte version (actual meaning of these two bytes may vary)
const int DATA_TAG_SIZE = 8; // 8byte tag
const int CHECKSUM_SIZE = 2; // 2byte checksum
char buffer[BUFFER_SIZE]; // used to store an incoming data frame
int buffer_index = 0;
unsigned testTag = 0;

void setup() {
  display.init();
  display.flipScreenVertically();
  display.drawString(0, 0, "Maker Access System");
  display.display();
  delay(900);
  
  pinMode(pinGreen, OUTPUT); // for status LEDs
  pinMode(pinRed, OUTPUT);
  pinMode(pinBlue, OUTPUT);
  pinMode(pinRelay, OUTPUT);
  
  RFID.begin(9600, SERIAL_8N1, 16, 17); // start serial to RFID reader
  Serial.begin(115200); // start serial to PC
  delay(100);
  Serial.println("Serials started");

  // We start by connecting to a WiFi network
  Serial.println();
  display.clear();
  String drawString = "Connecting to: ";
  drawString += ssid;
  display.drawStringMaxWidth(0, 0, 128, drawString);
  display.display();
  Serial.print("Connecting WiFi to ");
  Serial.println(ssid);
  display.clear();
  display.drawStringMaxWidth(0, 0, 128, "Start Wifi...");
  display.display();
  delay(1000);
  WiFi.begin(ssid, password);
  display.clear();
  display.drawStringMaxWidth(0, 0, 128, "WiFi started!");
  display.display();
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("Netmask: ");
  Serial.println(WiFi.subnetMask());
  Serial.print("Gateway: ");
  Serial.println(WiFi.gatewayIP());
  Serial.println();
  display.clear();
  display.drawStringMaxWidth(0, 0, 128, "WiFi connected!");
  display.drawStringMaxWidth(0, 12, 128, WiFi.localIP().toString());
  display.drawStringMaxWidth(0, 24, 128, WiFi.subnetMask().toString());
  display.drawStringMaxWidth(0, 36, 128, WiFi.gatewayIP().toString());
  display.display();  
  delay(2500);

  //Device is ready to read first RFID tag
  Serial.println("Waiting for RFID tag");
}

void loop() {
  changeStatusLED();
  if (millis() - startMillis >= TagLockout) {
    if (!deviceActive) {
      getRFID();
      if (testTag != 0) {
        if (logOn(testTag)) {
          testTag=0;
          startMillis = millis();
          deviceActive = true;
        } else {
          Serial.println("Logon failed!");
          display.clear();
          display.drawStringMaxWidth(0, 0, 128, "Error logging on");
          display.drawStringMaxWidth(0, 20, 128, "Reason:");
          display.drawStringMaxWidth(0, 32, 128, "__REASON__");
          display.display();
          flashLED(pinRed,3,200); 
          delay(2000);
          clearBuffer();
          testTag=0;
        }
      }
    } else {
      getRFID();
      if (testTag != 0) {
        if (logOff(testTag)) {
          startMillis = millis();
          deviceActive = false;
        } else {
          flashLED(pinRed,3,200);
          Serial.println("Logoff failed!");
          display.clear();
          display.drawStringMaxWidth(0, 0, 128, "Error logging off");
          display.drawStringMaxWidth(0, 20, 128, "Reason:");
          display.drawStringMaxWidth(0, 32, 128, "__REASON__");
          display.display();
          flashLED(pinRed,3,200); 
          delay(2000);
          clearBuffer();          
          testTag=0;
        }
      }
    }
  } else {
    getRFID();
    testTag=0;
  }

}
void clearBuffer() {
  int x = 0;
  Serial.print("Empty RFID buffer");
  while (RFID.available()) {
    x = RFID.read();
    Serial.print(".");
  }
  Serial.println("Buffer empty");
}
void getRFID() {
  if (RFID.available() > 0) {
    bool call_extract_tag = false;
    int ssvalue = RFID.read(); // read
    if (ssvalue == -1) { // no data was read
      return;
    }
    if (ssvalue == 2) { // RDM630/RDM6300 found a tag => tag incoming
      buffer_index = 0;
    } else if (ssvalue == 3) { // tag has been fully transmitted
      call_extract_tag = true; // extract tag at the end of the function call
    }
    if (buffer_index >= BUFFER_SIZE) { // checking for a buffer overflow (It's very unlikely that an buffer overflow comes up!)
      Serial.println("Error: Buffer overflow detected!");
      return;
    }

    buffer[buffer_index++] = ssvalue; // everything is alright => copy current value to buffer
    if (call_extract_tag == true) {
      if (buffer_index == BUFFER_SIZE) {
        unsigned tag = extract_tag();
        testTag = tag;
      } else { // something is wrong... start again looking for preamble (value: 2)
        buffer_index = 0;
        return;
      }
    }
  }
}

bool logOn(long tagID) {
  {
    Serial.print("connecting to ");
    Serial.println(host);

    // Use WiFiClient class to create TCP connections
    WiFiClient client;
    const int httpPort = 80;
    if (!client.connect(host, httpPort)) {
      Serial.println("connection failed");
      return false;
    }

    // We now create a URI for the request
    String url = subfolder;
    url += "/API/start_usage.php?tag=000";
    url += String(tagID);
    url += "&device=";
    url += deviceID;
    Serial.print("Requesting URL: ");
    Serial.println(url);

    // This will send the request to the server
    client.print(String("GET ") + url + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    Serial.println("GET Request sent - waiting for response...");
    delay(500);

    if (client.println() == 0) {
      Serial.println(F("Failed to send request"));
      return false;
    }
    // Check HTTP status
    char status[32] = {
      0
    };
    client.readBytesUntil('\r', status, sizeof(status));
    if (strcmp(status, "HTTP/1.1 200 OK") != 0) {
      Serial.print(F("Unexpected response: "));
      Serial.println(status);
      return false;
    }

    // Skip HTTP headers
    char endOfHeaders[] = "\r\n\r\n";
    if (!client.find(endOfHeaders)) {
      Serial.println(F("Invalid response"));
      return false;
    }

    const size_t bufferSize = JSON_OBJECT_SIZE(2) + 100;
    DynamicJsonBuffer jsonBuffer(bufferSize);

    // Parse JSON object
    JsonObject& root = jsonBuffer.parseObject(client);
    if (!root.success()) {
      Serial.println(F("Parsing failed!"));
      return false;
    }
    Serial.print(F("Response: "));
    String response = root["permit"].as<char*>();
    Serial.println(response);

    // Disconnect
    client.stop();

    if (response == "true") {
      activeSession = root["sessionID"].as<char*>();
      return true;
    } else {
      return false;
    }
  }
}
bool logOff(long tagID)
{
  Serial.print("connecting to ");
  Serial.println(host);

  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return false;
  }

  // We now create a URI for the request
  String url = subfolder;
  url += "/API/stop_usage.php?tag=000";
  url += String(tagID);
  url += "&sessionID=";
  url += activeSession;
  url += "&device=";
  url += deviceID;
  url += "&duration=";
  url += (millis() - startMillis) / 1000;
  Serial.print("Requesting URL: ");
  Serial.println(url);

  // This will send the request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");
  Serial.println("GET Request sent - waiting for response...");
  delay(500);

  if (client.println() == 0) {
    Serial.println(F("Failed to send request"));
    return false;
  }
  // Check HTTP status
  char status[32] = {
    0
  };
  client.readBytesUntil('\r', status, sizeof(status));
  if (strcmp(status, "HTTP/1.1 200 OK") != 0) {
    Serial.print(F("Unexpected response: "));
    Serial.println(status);
    return false;
  }

  // Skip HTTP headers
  char endOfHeaders[] = "\r\n\r\n";
  if (!client.find(endOfHeaders)) {
    Serial.println(F("Invalid response"));
    return false;
  }

  // Allocate JsonBuffer
  // Use arduinojson.org/assistant to compute the capacity.
  const size_t bufferSize = JSON_OBJECT_SIZE(1) + 20;
  DynamicJsonBuffer jsonBuffer(bufferSize);

  // Parse JSON object
  JsonObject& root = jsonBuffer.parseObject(client);
  if (!root.success()) {
    Serial.println(F("Parsing failed!"));
    return false;
  }
  // Extract values
  Serial.print(F("Response: "));
  String response = root["success"].as<char*>();
  Serial.println(response);

  // Disconnect
  client.stop();

  if (response == "true") {
    activeSession = "";
    return true;
  } else {
    return false;
  }


}
//HELPER FUNCTIONS
void changeStatusLED() {
  if (deviceActive) {
    digitalWrite(pinGreen, 1);
    digitalWrite(pinBlue, 0);
    digitalWrite(pinRelay, 1);
    display.clear();
    display.drawStringMaxWidth(0, 0, 128, "Running:");
    display.drawStringMaxWidth(0, 12, 128, "User: -=Username=-");
    unsigned long Now = (millis()-startMillis)/1000;
    int Seconds = Now%60;
    int Minutes = (Now/60)%60;
    int Hours = (Now/3600)%24;
    String runtime = String(Hours)+"h "+String(Minutes)+"min "+String(Seconds)+"s";
    display.drawStringMaxWidth(0, 24, 128, runtime);
    display.display();  
  } else {
    digitalWrite(pinGreen, 0);
    digitalWrite(pinBlue, 1);
    digitalWrite(pinRelay, 0);
    display.clear();
    display.drawStringMaxWidth(0, 0, 128, "Ready:");
    display.drawStringMaxWidth(0, 12, 128, "Waiting for RFID tag...");
    unsigned long Now = millis()/1000;
    int Seconds=Now%60;
    int Minutes = (Now/60)%60;
    int Hours = (Now/3600)%24;
    String waittime = String(Hours)+"h "+String(Minutes)+"min "+String(Seconds)+"s";
    display.drawStringMaxWidth(0, 48, 128, waittime);
    display.display();      
  }
}
void flashLED(int pinLED, int numFlashes, int interval) {
  bool state = digitalRead(pinLED);
  for (int i = 0; i < numFlashes; i++) {
    digitalWrite(pinLED, 1);
    delay(interval);
    digitalWrite(pinLED, 0);
    delay(interval);
  }
  digitalWrite(pinLED, state);
}
unsigned extract_tag() {
  char msg_head = buffer[0];
  char *msg_data = buffer + 1; // 10 byte => data contains 2byte version + 8byte tag
  char *msg_data_version = msg_data;
  char *msg_data_tag = msg_data + 2;
  char *msg_checksum = buffer + 11; // 2 byte
  char msg_tail = buffer[13];

  // print message that was sent from RDM630/RDM6300
  //Serial.println("--------");
  //Serial.print("Message-Head: ");
  //Serial.println(msg_head);
  //Serial.println("Message-Data (HEX): ");
  for (int i = 0; i < DATA_VERSION_SIZE; ++i) {
    //Serial.print(char(msg_data_version[i]));
  }
  //Serial.println(" (version)");
  for (int i = 0; i < DATA_TAG_SIZE; ++i) {
    //Serial.print(char(msg_data_tag[i]));
  }
  //Serial.println(" (tag)");
  //Serial.print("Message-Checksum (HEX): ");
  for (int i = 0; i < CHECKSUM_SIZE; ++i) {
    //Serial.print(char(msg_checksum[i]));
  }
  //Serial.println("");
  //Serial.print("Message-Tail: ");
  //Serial.println(msg_tail);
  //Serial.println("--");

  long tag = hexstr_to_value(msg_data_tag, DATA_TAG_SIZE);
  //Serial.print("Extracted Tag: ");
  //Serial.println(tag);
  long checksum = 0;
  for (int i = 0; i < DATA_SIZE; i += CHECKSUM_SIZE) {
    long val = hexstr_to_value(msg_data + i, CHECKSUM_SIZE);
    checksum ^= val;
  }
  //Serial.print("Extracted Checksum (HEX): ");
  //Serial.print(checksum, HEX);
  unsigned returnvalue = 0;
  if (checksum == hexstr_to_value(msg_checksum, CHECKSUM_SIZE)) { // compare calculated checksum to retrieved checksum
    //Serial.print(" (OK)"); // calculated checksum corresponds to transmitted checksum!
  } else {
    //Serial.print(" (NOT OK)"); // checksums do not match
  }
  //Serial.println("");
  //Serial.println("--------");
  return tag;
}
long hexstr_to_value(char *str, unsigned int length) { // converts a hexadecimal value (encoded as ASCII string) to a numeric value
  char *copy = (char*)malloc((sizeof(char) * length) + 1);
  memcpy(copy, str, sizeof(char) * length);
  copy[length] = '\0';
  // the variable "copy" is a copy of the parameter "str". "copy" has an additional '\0' element to make sure that "str" is null-terminated.
  long value = strtol(copy, NULL, 16);  // strtol converts a null-terminated string to a long value
  free(copy); // clean up
  return value;
}

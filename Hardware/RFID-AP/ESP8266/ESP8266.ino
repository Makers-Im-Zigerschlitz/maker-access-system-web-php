/*
    Simple HTTP get webclient test
*/

#include <ESP8266WiFi.h>
#include <SPI.h>
#include <SoftwareSerial.h>
#include <ArduinoJson.h>

const char* ssid     = "####SSID####";
const char* password = "####Password####"";
const char* host = "####Host####";
const int deviceID = ####Device-ID####;

const int pinRed = 13;
const int pinGreen = 12;
const int pinBlue = 14;

SoftwareSerial RFID(4, 5); // RX and TX

int data1 = 0;
int ok = -1;
String inputStringA = "";
long LastTag = 0;
long TagReset = 10000;
int InCount = 0;
String LastTagRX = "";

void setup() {
  pinMode(pinGreen, OUTPUT); // for status LEDs
  pinMode(pinRed, OUTPUT);
  pinMode(pinBlue, OUTPUT);

  RFID.begin(9600);    // start serial to RFID reader
  Serial.begin(115200); // start serial to PC
  delay(100);
  Serial.println("Serials started");


  // We start by connecting to a WiFi network
  Serial.println();
  Serial.print("Connecting WiFi to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.print("Netmask: ");
  Serial.println(WiFi.subnetMask());
  Serial.print("Gateway: ");
  Serial.println(WiFi.gatewayIP());

  Serial.println();
  Serial.println("Waiting for RFID tag");
  digitalWrite(pinBlue, 1);
}

int value = 0;

void loop() {
  readTags();
}

void readTags()
{
  while (RFID.available()) {
    digitalWrite(pinBlue, 0);
    char inCharA = (char)RFID.read();
    if (InCount >= 13) {
      InCount = 0;
      // read same tag once in 8 seconds, or a new tag right now
      if ((millis() - LastTag) >= TagReset || LastTag == 0 || inputStringA != LastTagRX)
      {
        ProcIDtag(inputStringA);
        LastTagRX = inputStringA;
        LastTag = millis();
      }
      inputStringA = "";
    }
    else {
      if (InCount != 0)
      {
        inputStringA += inCharA;
      }
      InCount++;
    }
    digitalWrite(pinBlue, 1);
  }

}

void ProcIDtag(String InputStr)
{
  InputStr = InputStr.substring(2, InputStr.length() - 2);
  Serial.print("ID: ");
  long tagID = hexToDec(InputStr);
  Serial.println(tagID);

  Serial.print("connecting to ");
  Serial.println(host);

  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return;
  }

  // We now create a URI for the request
  String url = "/API/verify_perm.php?tag=000";
  url += tagID;
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
    return;
  }
  // Check HTTP status
  char status[32] = {
    0
  };
  client.readBytesUntil('\r', status, sizeof(status));
  if (strcmp(status, "HTTP/1.1 200 OK") != 0) {
    Serial.print(F("Unexpected response: "));
    Serial.println(status);
    return;
  }

  // Skip HTTP headers
  char endOfHeaders[] = "\r\n\r\n";
  if (!client.find(endOfHeaders)) {
    Serial.println(F("Invalid response"));
    return;
  }

  // Allocate JsonBuffer
  // Use arduinojson.org/assistant to compute the capacity.
  const size_t capacity = JSON_OBJECT_SIZE(3) + JSON_ARRAY_SIZE(2) + 60;
  DynamicJsonBuffer jsonBuffer(capacity);

  // Parse JSON object
  JsonObject& root = jsonBuffer.parseObject(client);
  if (!root.success()) {
    Serial.println(F("Parsing failed!"));
    return;
  }
  // Extract values
  Serial.print(F("Response: "));
  String response = root["permit"].as<char*>();
  Serial.println(response);

  if (response=="true") {
    flashGreen(3,200);
  } else {
    flashRed(5,200);
  }

  // Disconnect
  client.stop();
}
long hexToDec(String hexString) {

  long decValue = 0;
  int nextInt;

  for (int i = 0; i < hexString.length(); i++) {

    nextInt = int(hexString.charAt(i));
    if (nextInt >= 48 && nextInt <= 57) nextInt = map(nextInt, 48, 57, 0, 9);
    if (nextInt >= 65 && nextInt <= 70) nextInt = map(nextInt, 65, 70, 10, 15);
    if (nextInt >= 97 && nextInt <= 102) nextInt = map(nextInt, 97, 102, 10, 15);
    nextInt = constrain(nextInt, 0, 15);

    decValue = (decValue * 16) + nextInt;
  }

  return decValue;
}
void flashRed(int numFlashes, int interval) {
  for (int i = 0; i < numFlashes; i++) {
    digitalWrite(pinRed, 1);
    delay(interval);
    digitalWrite(pinRed, 0);
    delay(interval);
  }
}
void flashGreen(int numFlashes, int interval) {
  for (int i = 0; i < numFlashes; i++) {
    digitalWrite(pinGreen, 1);
    delay(interval);
    digitalWrite(pinGreen, 0);
    delay(interval);
  }
}

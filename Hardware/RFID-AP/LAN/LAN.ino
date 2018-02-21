#include <SoftwareSerial.h>
#include <ArduinoJson.h>
#include <SPI.h>
#include <Ethernet.h>

const char* host = "####Host####";
const int deviceID = ####Device-ID####;

SoftwareSerial RFID(4,5); // RX and TX

int data1 = 0;
int ok = -1;
int yes = 7;
int no = 6;

String inputStringA = "";
long LastTag = 0;
long TagReset = 10000;
int InCount = 0;
String LastTagRX = "";


byte mac[] = { 
  0x90, 0xAD, 0xDA, 0x00, 0x98, 0x12 };
IPAddress ip(46,14,91,82);
char server[] = host;
EthernetClient client;

void setup()
{
  RFID.begin(9600);    // start serial to RFID reader
  Serial.begin(9600);  // start serial to PC 
  Serial.println("Serials started");
  while (!Serial) continue; 
  pinMode(yes, OUTPUT); // for status LEDs
  pinMode(no, OUTPUT);
  client.setTimeout(10000);
}

void ProcIDtag(String InputStr)
{
  InputStr = InputStr.substring(2,InputStr.length() - 2);
  Serial.print("ID: ");
  long tagID = hexToDec(InputStr);
  Serial.println(tagID);

  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to nonfigure Ethernet using DHCP");
    // try to congifure using IP address instead of DHCP:
    Ethernet.begin(mac, ip);
  }
  delay(1000);

  if (!client.connect(server, 80)) {
    Serial.println(F("Connection failed"));
    return;
  } 

  // Make a HTTP request:
  client.print("GET /API/verify_perm.php?tag=000");
  client.print(tagID);
  client.print("&device=");
  client.print(deviceID);
  client.println(" HTTP/1.0");
  client.print("Host: ");
  client.println(host);
  client.println("Connection: close");
  if (client.println() == 0) {
    Serial.println(F("Failed to send request"));
    return;
  }
  // Check HTTP status
  char status[32] = {
    0    };
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
  Serial.println(root["permit"].as<char*>());

  // Disconnect
  client.stop();
}

void readTags()
{
  while (RFID.available()) {
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
  }

}

void loop()
{
  readTags();
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


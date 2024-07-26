#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <ESP8266WebServer.h>
#include <SPI.h>
#include <MFRC522.h>

#define BUZZER D3
#define SS_PIN D4
#define RST_PIN D8

MFRC522 mfrc522(SS_PIN, RST_PIN);   // Create MFRC522 instance

#define WIFI_SSID "GENGGENG_4G"      // WIFI SSID
#define WIFI_PASSWORD "#G3ng2xW1F1"  // WIFI Password

#define SERVER_PORT 80
ESP8266WebServer server(SERVER_PORT);

String rfidUID = ""; // Global variable to store UID

void handleRoot() {
  String content = "" + rfidUID + "";

  // Add CORS headers
  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.sendHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
  server.sendHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  
  server.send(200, "text/html", content);
}

void handleGetUID() {
  // Add CORS headers
  server.sendHeader("Access-Control-Allow-Origin", "*");
  server.sendHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS");
  server.sendHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");

  server.send(200, "text/plain", rfidUID);
}

void setup() {
  Serial.begin(9600);

  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);  // Try to connect to WiFi
  Serial.print("Connecting to ");
  Serial.print(WIFI_SSID);

  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(500);
  }

  Serial.println();
  Serial.print("Connected to ");
  Serial.println(WIFI_SSID);
  Serial.print("IP Address is : ");
  Serial.println(WiFi.localIP());    // Print local IP address

  SPI.begin();      // Initiate SPI bus
  mfrc522.PCD_Init();   // Initiate MFRC522
  Serial.println("Put your card to the reader...");
  Serial.println();

  pinMode(BUZZER, OUTPUT);

  // Initialize web server
  server.on("/", handleRoot);
  server.on("/getUID", handleGetUID);
  server.begin();
}

void loop() {
  server.handleClient();

  // Look for new cards
  if (mfrc522.PICC_IsNewCardPresent() && mfrc522.PICC_ReadCardSerial()) {
    rfidUID = "";
    for (byte i = 0; i < mfrc522.uid.size; i++) {
      rfidUID += String(mfrc522.uid.uidByte[i] < 0x10 ? "0" : "");
      rfidUID += String(mfrc522.uid.uidByte[i], HEX);
    }
    rfidUID.toUpperCase();

    // Print UID to Serial Monitor
    Serial.println("UID read: " + rfidUID);

    // Trigger the buzzer if UID is detected
    digitalWrite(BUZZER, HIGH);
    delay(1000);
    digitalWrite(BUZZER, LOW);
    delay(1000);
  }
}

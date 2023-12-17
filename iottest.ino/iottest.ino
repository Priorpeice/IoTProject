#include <SPI.h>
#include <MFRC522.h>
#include <WiFiEsp.h>
#include <WiFiEspClient.h>
#include "SoftwareSerial.h"
#include <ArduinoJson.h>
#include <AES.h>
#include <AESLib.h>
#include <AES_config.h>

#define RST_PIN 9
#define SS_PIN 10

MFRC522 rfid(SS_PIN, RST_PIN);
SoftwareSerial Serial1(3, 4); // RX, TX
AES aes;

char ssid[] = "IoT";            // your network SSID (name)
char pass[] = "qwer1234";    // your network password
int status = WL_IDLE_STATUS;     // the Wifi radio's status
const int INPUTPINS =2;
const int LEDGREEN = 8;
byte cipher[32];
char b64[32];

boolean lastButton =LOW;
boolean currentButton=LOW;

char server[] = "192.168.219.101"; // 서버 주소 수정
int port = 8080; // 서버 포트 수정
String phpfile = "km.php";
byte nuidPICC[4];

WiFiEspClient client;

String key = "aaaaaaaaaaaaaaaa";
String ivs = "0123456789abcdef";

void turnON(int led){
    digitalWrite(led,1);
    delay(500);
    digitalWrite(led,0);
}
void changePhp()
{
  phpfile="index.php";
}

boolean debounce(boolean last)
{
  boolean current=digitalRead(INPUTPINS);
  if(last != current){
    delay(5);
    current=digitalRead(INPUTPINS);
  }
  return current;
}

void setup() {
  Serial.begin(9600);
  SPI.begin();
  rfid.PCD_Init();
  Serial1.begin(9600);
  pinMode(INPUTPINS, INPUT_PULLUP);
  pinMode(LEDGREEN,OUTPUT); //led
  setupWiFi();
  attachInterrupt(0,changePhp,CHANGE);
}
void setupWiFi()
 {
  
  WiFi.init(&Serial1);
  if (WiFi.status() == WL_NO_SHIELD) {
    Serial.println("WiFi shield not present");
    while (true);
  }

  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to WPA SSID: ");
    Serial.println(ssid);
    status = WiFi.begin(ssid, pass);
  }

  Serial.println("You're connected to the network");
}

void sendData(String uid, String php) 
{
  String encryptedUID = do_encrypt(uid, key, ivs);
  if (client.connect(server, port)) 
  {
    Serial.println("enc " + encryptedUID);

    String getRequest = "GET /" + php + "?uid=" + encryptedUID + " HTTP/1.1\r\n";
    Serial.println(getRequest);

    client.print(getRequest);
    client.print("Host: 192.168.219.101:8080\r\n\r\n");
    

    String responseText;
    while (client.connected()) {
      if (client.available()) {
        char c = client.read();
       Serial.print(c);
      }
    }
   
    client.stop();
   phpfile="km.php";
   
  } else 
  {
    Serial.println("Connection to server failed");
  }
}

void printHex(byte *buffer, byte bufferSize)
 {
  for (byte i = 0; i < bufferSize; i++) 
  {
    Serial.print(buffer[i] < 0x10 ? " 0" : " ");
    Serial.print(buffer[i], HEX);
  }
}

String getNUID(MFRC522::Uid uid) 
{
  String nuid = "";
  for (byte i = 0; i < uid.size; i++) 
  {
    nuid += String(uid.uidByte[i], HEX);
  }
  return nuid;
}

String do_encrypt(String msg, String key_str, String iv_str) 
{
  byte iv[16];
  memcpy(iv, (byte *)iv_str.c_str(), 16);

  int blen = base64_encode(b64, (char *)msg.c_str(), msg.length());

  aes.calc_size_n_pad(blen);
  int len = aes.get_size();
  byte plain_p[len];
  for (int i = 0; i < blen; ++i) plain_p[i] = b64[i];
  for (int i = blen; i < len; ++i) plain_p[i] = '\0';

  int blocks = len / 16;
  aes.set_key((byte *)key_str.c_str(), 16);
  aes.cbc_encrypt(plain_p, cipher, blocks, iv);

  base64_encode(b64, (char *)cipher, len);
  Serial.println("Encrypted Data output: " + String((char *)b64));
  return String((char *)b64);
}


void rfidChoice(String phpfile) {
  if (!rfid.PICC_IsNewCardPresent())
    return;

  if (!rfid.PICC_ReadCardSerial())
    return;

  MFRC522::PICC_Type piccType = rfid.PICC_GetType(rfid.uid.sak);

  if (piccType != MFRC522::PICC_TYPE_MIFARE_MINI &&
      piccType != MFRC522::PICC_TYPE_MIFARE_1K &&
      piccType != MFRC522::PICC_TYPE_MIFARE_4K) {
    Serial.println("Your tag is not of type MIFARE Classic.");
    return;
  }

  if (rfid.uid.uidByte[0] != nuidPICC[0] ||
      rfid.uid.uidByte[1] != nuidPICC[1] ||
      rfid.uid.uidByte[2] != nuidPICC[2] ||
      rfid.uid.uidByte[3] != nuidPICC[3]) {
    Serial.println("A new card has been detected.");
    for (byte i = 0; i < 4; i++) {
      nuidPICC[i] = rfid.uid.uidByte[i];
    }

    Serial.println("The NUID tag is:");
    Serial.print("In hex: ");
    printHex(rfid.uid.uidByte, rfid.uid.size);
    Serial.println();

    String nuid = getNUID(rfid.uid);
    String pfile = phpfile;
    sendData(nuid, pfile);
    turnON(LEDGREEN);
  } else {
    Serial.println("Card read previously.");
  }

  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}

void loop() {
   
    rfidChoice(phpfile);
   
   
    
}
 

# 웨어러블 스마트 월렛

## (Wearable Smart Wallet :결제 시스템)

## 💡 프로젝트 소개

- 결제기기를 휴대하기 어려운 장소 속에서 결제를 할 수 있도록 시스템을 구축하는 프로젝트 입니다.
- 장소 및 신체적 제약을 해결하기 위해 Arduino와 RFID 모듈을 이용하여 결제시스템을 설계 및 구현하였습니다.

## ⚙️ 프로젝트 개발 환경

- 프로그래밍 언어: php , C++
- 개발도구 : VS Code , Arduino IDE
- DBMS: Mysql
- 하드웨어 장비: Arduino uno 3, MFRC-522(RFID 모듈)

## 🧾 시스템 아키텍처

- 시스템 구조를 간단하게 그림으로 나타내보았습니다.

![architecture](https://github.com/user-attachments/assets/da495c93-83d8-4d37-8ea1-6f87adcc2863)
- 사용자는 RFID를 내장하고 있는 단말기를 이용해서 Arduino에 RFID 모듈과 통신을 진행하게됩니다.

     그리고 Arduino에서는 정보를 암호화하여 WS(WebServer)로 전송합니다.  WebServer에서는 정보 복호화, DB에 정보 저장, kakaoAPI를 호출을 통한 결제 정보를 사용자에게 송신합니다.

## **🔐 암호화**

- RFID 리더기에서 부터 받은 정보를 서버로 전송 시에도 보안을 갖춰야한다고 생각했습니다.

보안은 AES 대칭키 방식으로 암호화하고  다시 base 64로 인코딩하는 구조로 암호화를 진행하였습니다. 

## 📚 기능

- 사용자 정보 저장
    - 정보 저장의 흐름은 다음과 같습니다.
        
       ![Saving](https://github.com/user-attachments/assets/74ede075-30e1-47b0-9c89-9bebef8d8af5)
        
    
- 결제
    
    
- 결제의 흐름은 다음과 같습니다.
    
   ![payment](https://github.com/user-attachments/assets/54faa696-8ae1-4f7a-973a-a3907614c1af)
    
- 결제 과정에서는 실제 결제 API와 연동은 되어 있지 않으며 복잡한 결제 과정은 간략하게 구현해보았습니다.

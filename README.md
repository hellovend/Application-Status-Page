# 📋 FiveM 서버 지원자 관리 대시보드

FiveM 서버의 지원자 합격/불합격을 관리하고, Discord 웹훅을 통해 인사팀에 실시간으로 알림을 보내는 PHP 기반 웹 대시보드입니다.

---

## ✨ 주요 기능

- 🔐 **관리자 로그인** — 세션 기반 인증으로 관리자만 접근 가능
- ✅ **합격/불합격/지원불가 처리** — 고유번호 기반으로 지원자 결과 등록
- 🔍 **합격 여부 조회** — 지원자가 고유번호로 본인의 결과 확인
- 📢 **Discord 웹훅 알림** — 결과 등록 시 지정된 Discord 채널로 자동 알림
- 🚫 **IP 차단** — 비정상 접근 IP 차단 기능
- 🛠️ **서비스 점검/종료 모드** — 서비스 상태 플래그로 유지보수 페이지 전환
- 🗑️ **데이터 삭제** — 등록된 지원자 데이터 삭제 기능
- 🤖 **자동화** - 자동으로 DB에 추가
---

## 🔧 요구사항

- PHP 7.4 이상
- MySQL 5.7 이상
- 웹 서버 (Apache/Nginx)
- Discord 웹훅 URL (알림 기능 사용 시)

---

## 🚀 설치 및 설정

1. **프로젝트 다운로드**
   ```bash
   git https://github.com/hellovend/Application-Status-Page.git
   cd Application-Status-Page
   ```

2. **데이터베이스 설정**
   - MySQL 데이터베이스를 생성하세요.
   - `config/dbconfig.sample.php`를 `config/dbconfig.php`로 복사하고 실제 데이터베이스 정보를 입력하세요.

3. **Discord 웹훅 설정**
   - `config/discord_config.sample.php`를 `config/discord_config.php`로 복사하고 실제 Discord 웹훅 URL을 입력하세요.

4. **웹 서버 설정**
   - 프로젝트 폴더를 웹 서버의 루트 디렉토리에 배치하세요.
   - `.htpasswd` 파일을 생성하여 HTTP 기본 인증을 설정하세요 (선택사항).

5. **권한 설정**
   - `error.log` 파일이 쓰기 가능하도록 권한을 설정하세요.

6. **접근**
   - 브라우저에서 `login.php`에 접근하여 관리자 로그인 후 대시보드를 사용하세요.

---

## 📁 프로젝트 구조

```
Application-Status-Page/
├── admin/
│   └── index.php                  # 메인 관리자 대시보드
├── api/                           # API 엔드포인트 분리
│   ├── delete.php
│   ├── exam_results_insert.php
│   ├── numbers.php
│   ├── unique_numbers.php
│   ├── rejected.php
│   ├── check.php
│   ├── login.php
│   ├── admin.php
│   └── logins.php
├── assets/
│   ├── css/                       # 스타일시트
│   ├── script/                    # 자바스크립트
│   └── index.html
├── config/
│   ├── dbconfig.php               # 환경별 DB 설정 (Git 제외 권장)
│   ├── dbconfig.sample.php        # 데이터베이스 설정 샘플
│   ├── discord_config.php         # Discord 웹훅 설정 (Git 제외 권장)
│   ├── discord_config.sample.php  # Discord 웹훅 설정 샘플
│   ├── discord_webhook.php        # 메인 Discord 웹훅
│   ├── discord_webhook_admin_added.php    # 관리자 추가 알림 웹훅
│   ├── discord_webhook_ip_mismatch.php    # IP 불일치 알림 웹훅
│   └── .htpasswd                  # HTTP 기본 인증 파일 (Git 제외 권장)
├── includes/
│   └── discord_notification.php   # Discord 알림 공통 모듈
├── pages/
│   ├── 404.html                   # 404 에러 페이지
│   ├── faq.html                   # FAQ 페이지
│   ├── ipblock.html               # IP 차단 안내 페이지
│   ├── maintenance.html           # 서비스 점검 페이지
│   └── service_ended.html         # 서비스 종료 페이지
├── .gitignore                     # Git 무시 파일
└── README.MD                      # 이 파일
```

---

## ⚙️ 설치 및 설정

### 1. 파일 업로드
FileZilla 등 FTP 클라이언트를 이용해 웹 서버에 파일을 업로드합니다.
> 호스팅 설정 참고: [닷홈 호스팅 설정 영상](https://www.youtube.com/watch?v=cVMM9PXtWXI&t=1338s)

### 2. 데이터베이스 설정
`config/dbconfig.php` 파일을 열어 본인의 DB 정보로 수정합니다.

```php
$servername = "localhost";
$username   = "YOUR_DB_USERNAME";
$password   = "YOUR_DB_PASSWORD";
$dbname     = "YOUR_DB_NAME";
```

### 3. Discord 웹훅 설정
`config/discord_config.php`를 생성하고, 실제 Discord 웹훅 URL을 입력하세요. 필요에 따라 `config/discord_webhook.php` 및 관련 웹훅 파일도 확인해 주세요.

```php
$discordWebhook = "https://discord.com/api/webhooks/YOUR_WEBHOOK_URL";
```

> `config/dbconfig.php`와 `config/discord_config.php`는 개인 설정 파일이므로 `.gitignore`에 추가하는 것을 권장합니다.

### 4. 서버 로고 설정
`login.php`, `check.php`, `admin/index.php`에서 서버 로고 이미지를 교체합니다.

### 5. 서비스 상태 설정
`check.php`의 `$service_status` 변수로 서비스 상태를 제어합니다.

```php
$service_status = 0; // 0=정상, 1=점검, 2=종료
```

### 6. GCP 설정
구글 폼을 스트지와 연결 후 `Apps Script`에 
```JavaScript
function onFormSubmit(e) {

  if (!e || !e.response) {
    Logger.log("이벤트 없음");
    return;
  }

  var itemResponses = e.response.getItemResponses();

  var data = {
    timestamp: new Date().toISOString(),
    nickname: itemResponses[0].getResponse(),
    unique_number: itemResponses[1].getResponse(),
    age: itemResponses[2].getResponse()
  };

  UrlFetchApp.fetch("https://DOMAIN/sync.php", {
    method: "post",
    contentType: "application/json",
    payload: JSON.stringify(data)
  });
}
```
을 추가 한 후 트리거에서 추가합니다

### 7. 오류 발생 시 
디스코드 `heeeeeeeeeeello`에 DM으로 주십면 됩니다.

---

## 🗄️ 데이터베이스 구조

| 테이블명 | 설명 |
|---|---|
| `exam_results` | 합격/불합격 결과 저장 |
| `notpassed_candidates` | 지원불가자 목록 |

---

## ⚠️ 주의사항

- `config/dbconfig.php`와 `config/discord_config.php`에는 개인 설정 정보가 포함되어 있습니다. **절대 공개 저장소에 그대로 커밋하지 마세요.** `.gitignore`에 추가하거나 환경변수로 분리하는 것을 권장합니다.
- `phpinfo.php`는 서버 정보를 노출하므로 **배포 환경에서는 반드시 삭제**하세요.
- Discord 웹훅 URL도 코드에 직접 포함하지 말고 별도 설정 파일로 분리하는 것을 권장합니다.

---

## 📄 라이선스

이 프로젝트는 개인/커뮤니티 서버 용도로 자유롭게 사용할 수 있습니다.

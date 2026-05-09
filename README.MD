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
- 🤖 **자동화** — 구글 폼을 통해 제출 시 자동으로 데베에 등록

---

## 📁 프로젝트 구조

```
dashboard/
├── admin/                         # 관리자용 페이지 및 처리 스크립트
│   ├── admin.php                 # 관리자 대시보드 처리
│   ├── delete.php                # 데이터 삭제 처리
│   ├── exam_results_insert.php   # 합격/불합격 결과 등록 처리
│   ├── forms.php                 # 관리자 폼 페이지
│   ├── index.php                 # 관리자 메인 페이지
│   ├── login.php                 # 관리자 로그인 처리
│   ├── logins.php                # 로그인 정보 조회/관리
│   └── rejected.php              # 불합격 처리 페이지
├── api/                           # 클라이언트/서비스 연동 API 엔드포인트
│   ├── check.php                 # 지원자 결과 조회 및 서비스 상태 체크
│   ├── numbers.php               # 지원자 번호 확인 API
│   ├── phpinfo.php               # PHP 정보 출력 (배포 시 제거 권장)
│   ├── sumbit.php                # 외부 제출 데이터 수신
│   └── unique_numbers.php        # 고유번호 중복 검사 API
├── assets/                        # 정적 리소스 (CSS, JS, 이미지)
│   ├── css/
│   │   ├── check.css
│   │   ├── css.zip
│   │   ├── darkmode.css
│   │   ├── delete.css
│   │   ├── login.css
│   │   ├── logins.css
│   │   ├── LoginStyle.css
│   │   ├── registration.css
│   │   └── styles.css
│   ├── logo/                     # 서버 로고/이미지 리소스
│   ├── script/
│   │   ├── darkmode.js           # 다크모드 토글 JS
│   │   └── main.js               # 공통 UI 스크립트
│   └── index.html                # 정적 웹 페이지 또는 샘플 파일
├── config/                        # 환경 설정 및 웹훅 설정
│   ├── .htpasswd                 # HTTP 기본 인증 사용자 파일
│   ├── dbconfig.php              # 실제 DB 연결 설정
│   ├── dbconfig.sample.php       # DB 설정 샘플 파일
│   ├── discord_config.php        # Discord 웹훅 URL 설정
│   ├── discord_config.sample.php # Discord 설정 샘플 파일
│   ├── discord_webhook.php       # 메인 Discord 웹훅 전송 설정
│   ├── discord_webhook_admin_added.php # 관리자 추가 알림 웹훅
│   └── discord_webhook_ip_mismatch.php # IP 불일치 알림 웹훅
├── includes/                      # 공통 모듈 및 라이브러리
│   └── discord_notification.php   # Discord 알림 함수 공통화
├── pages/                         # 상태 페이지 템플릿
│   ├── 404.html                  # 404 에러 페이지
│   ├── faq.html                  # FAQ 페이지
│   ├── ipblock.html              # IP 차단 안내 페이지
│   ├── maintenance.html          # 서비스 점검 페이지
│   └── service_ended.html        # 서비스 종료 페이지
├── sync/                          # 자동 동기화 및 로그
│   ├── log.txt                   # 동기화 로그 파일
│   └── sync.php                  # 외부 데이터 동기화 엔드포인트
├── .gitignore                    # Git 무시 파일
├── DB.sql                        # 데이터베이스 생성/스키마 파일
├── index.php                    # 사용자용 메인 진입 페이지
├── login.php                    # 사용자 로그인/조회 페이지
├── README.MD                    # 프로젝트 설명 파일
└── server.bat                   # 로컬 개발/테스트용 실행 배치
```

---

## ⚙️ 설치 및 설정

### 요구사항
- PHP 7.4 이상
- MySQL / MariaDB
- 웹 서버 (Apache 권장, `.htaccess` 지원 필요)

### 0. 파일 다운로드
```cmd
git clone https://github.com/hellovend/Application-Status-Page.git
cd Application-Status-Page
```

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

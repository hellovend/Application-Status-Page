# 📋 FiveM 서버 지원자 관리 대시보드

FiveM 서버의 지원자 합격/불합격을 관리하고, Discord 웹훅을 통해 인사팀에 실시간으로 알림을 보내는 PHP 기반 웹 대시보드입니다.

---

## ✨ 주요 기능

- 🔐 **관리자 로그인** — 세션 기반 인증으로 관리자만 접근 가능하며, IP 기반 접근 제어 지원
- ✅ **합격/불합격/지원불가 처리** — 고유번호 기반으로 지원자 결과 등록 (UPsert 방식, 중복 등록 시 자동 업데이트)
- 🔍 **합격 여부 조회** — 지원자가 고유번호로 본인의 결과 확인 (`passed` / `failed` / `pending`)
- 📢 **Discord 웹훅 알림** — 결과 등록 시 지정된 Discord 채널로 Embed 형식 자동 알림
- 🚫 **IP 차단** — SQL 인젝션 시도 등 비정상 접근 IP를 `blocked_ips` 테이블에 자동 등록
- 🛠️ **서비스 상태 제어** — `$service_status` 변수로 정상/점검/종료 모드 전환
- 🗑️ **데이터 삭제** — 등록된 지원자 데이터 삭제 기능
- 🤖 **구글 폼 자동화** — Google Apps Script를 통해 지원서 제출 시 `sync/sync.php`로 자동 DB 등록
- 🚫 **지원불가자 접속 차단** *(추가 예정)* — `notpassed_candidates`에 등록된 번호로 조회 시 차단 페이지로 리다이렉트
- 📣 **공지 시스템** *(추가 예정)* — 관리자가 등록한 공지를 조회 화면 상단 중앙에 배너로 표시
- 📫 **이메일 통한 안내** *(추가 예정)* — 구글 폼으로 저장된 이메일로 안내와 등록 완료를 알려주는 시스템

---

## 🗺️ 서비스 흐름

```
[지원자] → Google Form 제출
    └→ Apps Script → sync/sync.php → candidates 테이블 (pending 상태)

[관리자] → admin/index.php 로그인
    └→ 고유번호 입력 + 결과 선택 (합격/불합격/지원불가)
        └→ exam_results 테이블 UPsert
        └→ Discord 웹훅 알림 전송

[지원자] → api/check.php에서 고유번호 조회
    └→ exam_results에서 pass_status 확인
        └→ 합격 / 불합격 / 검토중 / 오류 결과 화면 표시
```

---

## 📁 프로젝트 구조

```
Application-Status-Page/
├── admin/                         # 관리자용 페이지 및 처리 스크립트
│   ├── admin.php                 # 관리자 계정/IP 관리
│   ├── delete.php                # 데이터 삭제 처리
│   ├── exam_results_insert.php   # 합격/불합격 결과 등록 처리
│   ├── forms.php                 # 관리자 폼 페이지
│   ├── index.php                 # 관리자 메인 페이지 (결과 등록 UI + UPsert 로직)
│   ├── login.php                 # 관리자 로그인 처리
│   ├── logins.php                # 로그인 정보 조회/관리
│   └── rejected.php              # 불합격 처리 페이지
├── api/                           # 클라이언트/서비스 연동 API 엔드포인트
│   ├── check.php                 # 지원자 결과 조회 및 서비스 상태 체크
│   ├── numbers.php               # 합격/불합격/지원불가자 번호 목록 조회
│   ├── phpinfo.php               # PHP 정보 출력 (⚠️ 배포 시 반드시 삭제)
│   ├── rejected.php              # 지원불가자 API
│   ├── sumbit.php                # 외부 제출 데이터 수신
│   └── unique_numbers.php        # 고유번호 중복 검사 API
├── assets/                        # 정적 리소스 (CSS, JS, 이미지)
│   ├── css/
│   │   ├── check.css             # 조회 페이지 스타일
│   │   ├── darkmode.css          # 다크모드 CSS 변수
│   │   ├── delete.css
│   │   ├── login.css
│   │   ├── logins.css
│   │   ├── LoginStyle.css
│   │   ├── registration.css      # 결과 등록 페이지 스타일
│   │   └── styles.css
│   ├── logo/                     # 서버 로고/이미지 리소스
│   ├── script/
│   │   ├── darkmode.js           # 다크모드 토글 JS
│   │   └── main.js               # 공통 UI 스크립트
│   └── index.html
├── config/                        # 환경 설정 및 웹훅 설정
│   ├── dbconfig.sample.php       # DB 설정 샘플 파일
│   ├── discord_config.sample.php # Discord 설정 샘플 파일
│   ├── discord_webhook.php       # 메인 Discord 웹훅 전송
│   ├── discord_webhook_admin_added.php    # 관리자 추가 알림 웹훅
│   └── discord_webhook_ip_mismatch.php   # IP 불일치 알림 웹훅
├── includes/
│   └── discord_notification.php   # Discord 알림 함수 공통 모듈
├── pages/                         # 상태 페이지 템플릿
│   ├── 404.html
│   ├── faq.html
│   ├── ipblock.html              # IP 차단 안내 페이지
│   ├── maintenance.html          # 서비스 점검 페이지
│   └── service_ended.html        # 서비스 종료 페이지
├── sync/
│   ├── log.txt                   # 동기화 로그 파일
│   └── sync.php                  # 구글 폼 → DB 자동 동기화 엔드포인트
├── DB.sql                        # 데이터베이스 전체 스키마 (샘플 데이터 포함)
├── index.php                    # 사용자용 메인 진입 페이지
├── login.php                    # 사용자 로그인/조회 페이지
└── server.bat                   # 로컬 개발/테스트용 실행 배치
```

---

## ⚙️ 설치 및 설정

### 요구사항

- PHP 7.4 이상 (PHP 8.x 권장, `match` 표현식 사용)
- MySQL 5.7+ / MariaDB 10.3+
- 웹 서버 (Apache 권장, `.htaccess` 지원 필요)
- cURL 확장 (Discord 웹훅 전송에 필요)

---

### 0. 파일 다운로드

```bash
git clone https://github.com/hellovend/recruit-dashboard.git
cd recruit-dashboard
```

---

### 1. 파일 업로드

FileZilla 등 FTP 클라이언트를 이용해 웹 서버에 파일을 업로드합니다.

> 호스팅 설정 참고: [닷홈 호스팅 설정 영상](https://www.youtube.com/watch?v=cVMM9PXtWXI&t=1338s)

---

### 2. 데이터베이스 설정

`DB.sql`을 MySQL에 임포트하면 아래 테이블이 자동 생성됩니다.

| 테이블명 | 설명 |
|---|---|
| `users` | 인사팀 로그인 계정 |
| `admins` | 관리자 계정 및 접속 허용 IP |
| `blocked_ips` | 차단된 IP 목록 |
| `candidates` | 구글 폼을 통해 수신된 지원자 원본 데이터 |
| `exam_results` | 합격 / 불합격 심사 결과 |
| `notpassed_candidates` | 지원불가자 목록 (= `rejected_applicants` 뷰로도 접근 가능) |

이후 `config/dbconfig.php`에 DB 접속 정보를 입력하세요.

```php
$servername = "localhost";
$username   = "YOUR_DB_USERNAME";
$password   = "YOUR_DB_PASSWORD";
$dbname     = "YOUR_DB_NAME";
```

---

### 3. Discord 웹훅 설정

`config/discord_config.sample.php`를 복사해 `config/discord_config.php`를 만들고 웹훅 URL을 입력합니다.

```php
$discordWebhook = "https://discord.com/api/webhooks/YOUR_WEBHOOK_URL";
```

> `config/dbconfig.php`와 `config/discord_config.php`는 개인 설정 파일이므로 반드시 `.gitignore`에 추가하세요.

---

### 4. 서버 로고 설정

'../assets/logo/logo.png' 해당 파일을 교체하십쇼

---

### 5. 서비스 상태 설정

`api/check.php`의 `$service_status` 변수로 서비스 상태를 제어합니다.

```php
$service_status = 0; // 0 = 정상, 1 = 점검, 2 = 종료
```

| 값 | 동작 |
|---|---|
| `0` | 정상 서비스 |
| `1` | `pages/maintenance.html`로 리다이렉트 |
| `2` | `pages/service_ended.html`로 리다이렉트 |

---

### 6. Google Apps Script (구글 폼 자동화) 설정

구글 폼을 스프레드시트와 연결한 후, **Apps Script 편집기**에 아래 코드를 추가하고 **onFormSubmit 트리거**를 등록합니다.

```javascript
function onFormSubmit(e) {
  if (!e || !e.response) {
    Logger.log("이벤트 없음");
    return;
  }

  var itemResponses = e.response.getItemResponses();

  // ⚠️ 폼 질문 순서에 맞게 인덱스를 조정하세요
  var data = {
    timestamp:     new Date().toISOString(),
    nickname:      itemResponses[0].getResponse(), // 닉네임
    unique_number: itemResponses[1].getResponse(), // 고유번호
    age:           itemResponses[2].getResponse()  // 나이
  };

  UrlFetchApp.fetch("https://YOUR_DOMAIN/sync/sync.php", {
    method:      "post",
    contentType: "application/json",
    payload:     JSON.stringify(data)
  });
}
```

> 트리거 등록 경로: Apps Script → 시계 아이콘(트리거) → `onFormSubmit` 함수, 이벤트 유형: **양식 제출 시**

---

## 🗄️ pass_status 상태값 정의

`exam_results.pass_status` 컬럼에서 사용하는 값은 다음과 같습니다.

| 값 | 화면 표시 | 설명 |
|---|---|---|
| `passed` | ✅ 합격 | 면접 일정 DM 전달 안내 |
| `failed` | ❌ 불합격 | 3일 후 재지원 가능 안내 |
| `pending` | 🕐 검토 중 | 현재 인사팀 검토 중 |
| (없음) | ⚠️ 오류 | 미지원 또는 등록 오류 |

---

## ⚠️ 주의사항 및 보안

- `config/dbconfig.php`, `config/discord_config.php`에는 민감한 정보가 포함됩니다. **절대 공개 저장소에 커밋하지 마세요.** `.gitignore`에 반드시 추가하세요.
- `api/phpinfo.php`는 서버 환경 정보를 노출하므로 **배포 환경에서는 반드시 삭제**하세요.
- `DB.sql`의 샘플 데이터(`users` 테이블 초기 계정 등)는 운영 DB 적용 전에 삭제하거나 비밀번호를 변경하세요.
- 현재 비밀번호가 평문으로 저장됩니다. 운영 환경에서는 `password_hash()` / `password_verify()` 사용을 강력히 권장합니다.
- Discord 웹훅 URL도 코드에 직접 포함하지 말고 `config/discord_config.php`로 분리해서 관리하세요.

---

## 🌍 테스트 서버 접속

- [메인 페이지](https://pass.hellovend.xyz/)
- [관리자 페이지](https://pass.hellovend.xyz/login.php) <- admin / admin1234
- [구글 폼 페이지](https://forms.gle/P8k45qJ3nXNZWAn28)

---

## 📄 라이선스

이 프로젝트는 b1n2da의 원본 코드를 기반으로 수정 및 개선되었습니다.
원본은 디스코드 서버 부스터 한정으로 배포되었으나 현재 해당 서버가 존재하지 않으며, 별도의 라이선스 명시가 없었습니다. 원작자의 기여를 존중하며 출처를 밝힙니다.
개인 및 커뮤니티 서버 용도로 자유롭게 사용할 수 있습니다. 단, 상업적 이용은 삼가주세요

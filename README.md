# QR 코드 생성기

비로그인 사용자도 즉시 사용할 수 있는, 간편하고 빠른 QR 코드 생성 웹 서비스입니다.

## 주요 기능
- 텍스트, URL 등 다양한 입력값을 QR 코드로 변환
- 실시간 미리보기 및 PNG/JPG 다운로드
- 로그인/회원가입 없이 누구나 사용 가능
- 반응형 UI (모바일/PC 최적화)
- QR 코드 색상, 크기, 여백 등 커스터마이즈 옵션(선택)

## 기술 스택
- **Backend**: Laravel 12
- **Frontend**: Livewire, Volt
- **QR 코드 생성**: [quar](https://github.com/tuncaybahadir/quar) 패키지
- **PHP**: 8.3 이상

## 설치 및 실행 방법

### 1. 저장소 클론
```bash
git clone [YOUR_REPO_URL]
cd [YOUR_PROJECT_FOLDER]
```

### 2. 의존성 설치
```bash
composer install
npm install && npm run build
```

### 3. 환경설정
```bash
cp .env.example .env
php artisan key:generate
```

### 4. quar 패키지 설치
```bash
composer require tuncaybahadir/quar
```

### 5. 서버 실행
```bash
php artisan serve
```

## quar 패키지 간단 사용법
```php
use tbQuar\Facades\Quar;

// 기본 QR 코드 생성
$qr = Quar::generate('생성할 텍스트 또는 URL');

// Blade에서 출력
<div>
    {{ $qr }}
</div>
```

더 다양한 옵션 및 커스터마이징은 [공식 문서](https://github.com/tuncaybahadir/quar)를 참고하세요.

## 참고 링크
- quar 공식 문서: https://github.com/tuncaybahadir/quar
- Laravel: https://laravel.com/
- Livewire: https://livewire.laravel.com/
- Volt: https://volt.laravel.com/

---
문의/기여는 PR 또는 Issue로 남겨주세요. 
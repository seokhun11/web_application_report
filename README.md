# 💻 CodeReviewBoard

코드 리뷰 커뮤니티 게시판 - PHP 기반 웹 애플리케이션

## 📋 프로젝트 소개

개발자들이 자신의 코드를 공유하고 다른 개발자들로부터 리뷰를 받을 수 있는 커뮤니티 게시판입니다.

## ✨ 주요 기능

### 사용자 인증
- 회원가입 (비밀번호 해시화 저장)
- 로그인 / 로그아웃
- 세션 기반 인증

### 게시글 관리
- 게시글 작성 (제목, 내용, 코드, 프로그래밍 언어 선택)
- 게시글 수정 / 삭제 (작성자 본인만 가능)
- 게시글 목록 조회
- 게시글 상세 보기 (조회수 자동 증가)

### 댓글 시스템
- 코드 리뷰 댓글 작성
- 로그인 사용자는 사용자명 표시, 비로그인은 익명 처리

### 지원 프로그래밍 언어
- Python, JavaScript, Java, C, C++, C#
- PHP, HTML, CSS, SQL
- Go, Rust, TypeScript, Kotlin, Swift

## 🛠 기술 스택

| 분류 | 기술 |
|------|------|
| Backend | PHP 7.4+ |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3 |
| Server | Apache (XAMPP) |

## 📁 프로젝트 구조

```
├── includes/
│   ├── header.php      # 공통 헤더 컴포넌트
│   └── footer.php      # 공통 푸터 컴포넌트
├── config.php          # DB 설정 및 세션 초기화
├── index.php           # 메인 페이지 (게시글 목록)
├── view.php            # 게시글 상세 보기
├── write.php           # 게시글 작성
├── edit.php            # 게시글 수정
├── delete.php          # 게시글 삭제
├── login.php           # 로그인
├── register.php        # 회원가입
├── logout.php          # 로그아웃
├── style.css           # 스타일시트
└── database.sql        # DB 스키마
```

## 🚀 설치 방법

### 1. 요구사항
- XAMPP (Apache + MySQL + PHP) 또는 동등한 환경
- PHP 7.4 이상
- MySQL 5.7 이상 / MariaDB 10.3 이상

### 2. 설치 단계

```bash
# 1. 프로젝트 클론
git clone https://github.com/yourusername/CodeReviewBoard.git

# 2. XAMPP test 폴더로 이동
mv test /path/to/xampp/test/

# 3. XAMPP 실행 (Apache, MySQL)
```
### 3. 접속

    로컬용 : http://localhost/test
    
## 📸 스크린샷

### 메인 페이지
게시글 목록을 카드 형태로 표시

### 게시글 상세
코드 하이라이팅 및 댓글 기능

### 로그인 / 회원가입
깔끔한 폼 디자인

## 🎨 디자인 특징

- **다크 테마**: 개발자 친화적인 어두운 배경
- **커스텀 폰트**: Cafe24ProSlim (일반 텍스트), IntelOneMonoItalic (코드)
- **반응형 디자인**: 모바일 환경 지원
- **그라데이션 효과**: 헤더 및 버튼에 적용

## 🔒 보안

- 비밀번호 해시화 (`password_hash`, `password_verify`)
- SQL Injection 방지 (PDO Prepared Statements)
- XSS 방지 (`htmlspecialchars`)
- CSRF 방지 (세션 기반)

## 📝 라이선스

MIT License

## 👤 개발자

- GitHub: [@seokhun11](https://github.com/seokhun11)

---

⭐ 이 프로젝트가 도움이 되셨다면 Star를 눌러주세요!

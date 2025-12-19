<?php
/**
 * 데이터베이스 설정 파일
 * MySQL 연결 정보를 관리합니다.
 */

// 데이터베이스 연결 정보
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // MySQL 사용자명
define('DB_PASS', 'gkfn010906@');              // MySQL 비밀번호 (XAMPP 기본값은 빈 문자열)
define('DB_NAME', 'code_review_community');
define('DB_CHARSET', 'utf8mb4');

// 데이터베이스 연결 함수
function getDBConnection()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("데이터베이스 연결 실패: " . $e->getMessage());
    }
}

// 사이트 설정
define('SITE_NAME', '코드 리뷰 커뮤니티');
define('SITE_URL', 'http://localhost/test');

// 세션 시작
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 에러 표시 설정 (개발 중에만 사용)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
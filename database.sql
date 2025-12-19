-- ============================================
-- 코드 리뷰 커뮤니티 데이터베이스 스키마
-- ============================================

-- 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS code_review_community CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE code_review_community;

-- ============================================
-- 사용자 테이블
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- ============================================
-- 게시글 테이블
-- ============================================
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    code_content TEXT,
    programming_language VARCHAR(50) DEFAULT 'plaintext',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- ============================================
-- 댓글 테이블
-- ============================================
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- ============================================
-- 테스트용 기본 사용자 추가
-- ============================================
INSERT INTO
    users (username, password, email)
VALUES (
        'admin',
        'admin123',
        'admin@example.com'
    ),
    (
        'testuser',
        'test123',
        'test@example.com'
    );

-- ============================================
-- 테스트용 게시글 추가
-- ============================================
INSERT INTO
    posts (
        user_id,
        title,
        content,
        code_content,
        programming_language
    )
VALUES (
        1,
        'Python 함수 리뷰 요청',
        '이 Python 함수의 효율성을 검토해주세요. 개선할 부분이 있을까요?',
        'def fibonacci(n):
    if n <= 1:
        return n
    return fibonacci(n-1) + fibonacci(n-2)

# 사용 예시
print(fibonacci(10))',
        'python'
    ),
    (
        2,
        'JavaScript 비동기 처리 질문',
        'async/await 사용법이 맞는지 확인 부탁드립니다.',
        'async function fetchData() {
    try {
        const response = await fetch("/api/data");
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Error:", error);
    }
}',
        'javascript'
    );

-- ============================================
-- 테스트용 댓글 추가
-- ============================================
INSERT INTO
    comments (post_id, user_id, content)
VALUES (
        1,
        2,
        '메모이제이션을 사용하면 성능이 크게 향상됩니다!'
    ),
    (1, 1, '감사합니다! 적용해보겠습니다.');
// 전역 변수로 현재 학생 이름을 저장합니다.
let currentStudentName = '익명';

/**
 * [새로운 목표 함수]
 * XSS 공격 성공 시 비밀 관리자 페이지로 리디렉션시키는 함수입니다.
 */
function accessAdminPanel() {
    console.log("관리자 패널 접근 시도...");
    // 인증 키와 함께 숨겨진 관리자 페이지로 이동시킵니다.
    window.location.href = 'admin_page.php?auth=success';
}

/**
 * [보안 적용됨]
 * URL 파라미터에서 학생 이름을 가져와 안전하게 환영 메시지를 표시합니다.
 */
function displayWelcome() {
    const welcomeBox = document.getElementById('welcome-message');
    const params = new URLSearchParams(window.location.search);
    const studentName = params.get('student_name');

    if (studentName && welcomeBox) {
        currentStudentName = studentName;
        
        const header = document.createElement('h2');
        const paragraph = document.createElement('p');

        header.innerText = `환영합니다, ${studentName} 님!`;
        paragraph.innerText = 'Basic 과정에 오신 것을 환영합니다.';

        welcomeBox.innerHTML = '';
        welcomeBox.appendChild(header);
        welcomeBox.appendChild(paragraph);
    }
}

/**
 * [취약점 존재]
 * 질문 입력창의 내용을 미리보기 영역에 표시합니다.
 * 'a' 태그 외 다른 태그를 차단하는 필터링 로직이 적용되어 있습니다.
 */
function handlePreview() {
    const commentInput = document.getElementById('comment-input');
    const previewArea = document.getElementById('preview-area');
    let userComment = commentInput.value;
    let isBlocked = false;

    // 1단계: 허용되지 않은 태그 검사
    const withoutATags = userComment.replace(/<\/?a[^>]*>/gi, '');
    if (/<[^>]+>/.test(withoutATags)) {
        isBlocked = true;
    }

    // 2단계: 금지된 속성 검사
    if (!isBlocked) {
        const attributeBlacklist = [/onerror/gi, /onload/gi, /script/gi, /src/gi, /href/gi];
        attributeBlacklist.forEach(pattern => {
            if (pattern.test(userComment)) {
                isBlocked = true;
            }
        });
    }

    // 최종 차단 여부 처리
    if (isBlocked) {
        previewArea.innerText = "미리보기 실패: 허용되지 않는 태그나 속성이 포함되어 있습니다.";
    } else {
        // 필터링 통과 시 innerHTML 사용 (<a> 태그를 이용한 공격만 가능)
        previewArea.innerHTML = userComment; 
    }
}

/**
 * 작성된 질문을 질문 목록에 추가합니다.
 */
function handleQuestionSubmit() {
    const commentInput = document.getElementById('comment-input');
    const previewArea = document.getElementById('preview-area');
    const commentsSection = document.getElementById('comments-section');

    let content = previewArea.innerHTML || commentInput.value;
    if (!content || previewArea.innerText.startsWith("미리보기 실패")) {
        if (commentInput.value) {
            content = commentInput.value;
        } else {
            alert('등록할 질문이 없습니다.');
            return;
        }
    }

    const questionEntry = document.createElement('div');
    questionEntry.className = 'question-entry';

    const metaDiv = document.createElement('div');
    metaDiv.className = 'question-meta';
    const authorSpan = document.createElement('span');
    authorSpan.className = 'author';
    authorSpan.innerText = currentStudentName;
    const timeSpan = document.createElement('span');
    timeSpan.className = 'timestamp';
    timeSpan.innerText = new Date().toLocaleString();
    
    metaDiv.appendChild(authorSpan);
    metaDiv.appendChild(timeSpan);

    const contentDiv = document.createElement('div');
    contentDiv.className = 'question-content';
    if (previewArea.innerHTML && !previewArea.innerText.startsWith("미리보기 실패")) {
        contentDiv.innerHTML = previewArea.innerHTML;
    } else {
        contentDiv.innerText = commentInput.value;
    }
    
    questionEntry.appendChild(metaDiv);
    questionEntry.appendChild(contentDiv);
    commentsSection.appendChild(questionEntry);
    
    commentInput.value = '';
    previewArea.innerHTML = '';
    alert('질문이 등록되었습니다!');
}

// HTML 문서가 완전히 로드된 후 스크립트를 실행합니다.
document.addEventListener('DOMContentLoaded', () => {
    displayWelcome();

    const previewBtn = document.getElementById('preview-btn');
    const submitBtn = document.getElementById('submit-btn');

    if (previewBtn) {
        previewBtn.addEventListener('click', handlePreview);
    }
    if (submitBtn) {
        submitBtn.addEventListener('click', handleQuestionSubmit);
    }
});
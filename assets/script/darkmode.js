// ===== DARK MODE TOGGLE SCRIPT =====
// HTML의 <head>에 darkmode.css를 먼저 링크하고,
// 이 스크립트를 </body> 직전에 추가하세요.

(function () {
    const STORAGE_KEY = "dm-theme";

    // 저장된 테마 또는 시스템 기본값 불러오기
    function getPreferred() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) return saved;
        return window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light";
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute("data-theme", theme);
        localStorage.setItem(STORAGE_KEY, theme);

        const label = document.getElementById("dm-label");
        if (label) label.textContent = theme === "dark" ? "라이트 모드" : "다크 모드";
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute("data-theme");
        applyTheme(current === "dark" ? "light" : "dark");
    }

    // ✅ 렌더 전에 테마 즉시 적용 (흰 화면 깜빡임 방지)
    applyTheme(getPreferred());

    // DOM 로드 후 토글 버튼 삽입
    document.addEventListener("DOMContentLoaded", function () {

        const btn = document.createElement("button");
        btn.id = "darkmode-toggle";
        btn.setAttribute("aria-label", "다크 모드 토글");
        btn.innerHTML = `
            <span class="dm-icon dm-icon-sun">☀️</span>
            <span class="dm-icon dm-icon-moon">🌙</span>
            <span class="dm-switch"></span>
            <span class="dm-label" id="dm-label">다크 모드</span>
        `;
        btn.addEventListener("click", toggleTheme);
        document.body.appendChild(btn);
    });
})();
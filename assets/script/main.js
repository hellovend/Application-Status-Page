document.addEventListener('DOMContentLoaded', () => {
    const closeBtn = document.getElementById('close-btn');
    const wrap2 = document.getElementById('wrap2');

    if (closeBtn && wrap2) {
        closeBtn.addEventListener('click', () => {
            wrap2.style.display = 'none';
        });
    }
});

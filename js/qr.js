document.addEventListener("DOMContentLoaded", () => {
    const qrContainer = document.getElementById("qrcode");
    if (qrContainer) {
        // 获取当前页面的完整真实 URL（自动适应 localhost 或线上域名）
        const currentUrl = window.location.href;

        // 使用 qrcode.js 生成二维码
        new QRCode(qrContainer, {
            text: currentUrl,
            width: 140,
            height: 140,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }
});
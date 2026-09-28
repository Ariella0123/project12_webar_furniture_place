document.addEventListener("DOMContentLoaded", () => {
    const qrContainer = document.getElementById("qrcode");
    if (qrContainer) {
        // 动态使用当前浏览器的域名与URL, 严禁硬编码 localhost
        const currentUrl = encodeURIComponent(window.location.href);
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${currentUrl}`;
        qrContainer.innerHTML = `<img src="${qrApiUrl}" alt="Scan QR Code to open on Mobile">`;
    }
});
document.addEventListener("DOMContentLoaded", () => {
    const modelViewer = document.querySelector("#furniture-viewer");
    const fullscreenBtn = document.querySelector("#fullscreen-btn");
    const resetCamBtn = document.querySelector("#reset-cam-btn");

    if (modelViewer) {
        // 1. 全屏功能
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener("click", () => {
                if (!document.fullscreenElement) {
                    modelViewer.requestFullscreen().catch(err => {
                        alert(`无法进入全屏: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen();
                }
            });
        }

        // 2. 重置相机视角
        if (resetCamBtn) {
            resetCamBtn.addEventListener("click", () => {
                modelViewer.cameraOrbit = "0deg 75deg 105%";
                modelViewer.fieldOfView = "auto";
            });
        }
    }
});
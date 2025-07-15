<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Photobooth Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4">
    <h1 class="text-4xl font-bold text-gray-800 mb-6">Photobooth Pro</h1>

    <div class="w-full max-w-5xl flex flex-col md:flex-row gap-8">
        <div class="w-full md:w-1/2 bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Live Camera</h2>
            <div class="relative w-full aspect-video bg-gray-200 rounded-lg overflow-hidden">
                <video id="video" class="w-full h-full object-cover transform scale-x-[-1]" autoplay muted></video>
                <div id="countdown"
                    class="absolute inset-0 flex items-center justify-center text-8xl font-bold text-white"
                    style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);"></div>
            </div>
            <div id="status-text" class="h-8 mt-4 text-lg font-medium text-blue-600"></div>
            <button id="capture-btn"
                class="w-full mt-2 py-4 px-6 text-xl font-bold text-white bg-blue-500 rounded-lg shadow-md hover:bg-blue-600 transition-all duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed">
                Memuat...
            </button>
        </div>

        <div class="w-full md:w-1/2 bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Hasil Foto</h2>
            <canvas id="result-canvas" class="w-full rounded-lg shadow-inner"></canvas>
            <a id="download-link"
                class="hidden mt-4 w-full text-center py-3 px-6 text-lg font-bold text-white bg-green-500 rounded-lg shadow-md hover:bg-green-600 transition-all duration-300"
                download="hasil-photobooth.png">
                Unduh Hasil Foto
            </a>
        </div>
    </div>

    <script>
        const FRAME_URL = "{{ asset('storage/' . $data->image) }}";
        const BORDER_TOP = "{{ $data->border_top }}";
        const BORDER_BOTTOM = "{{ $data->border_bottom }}";
        const BORDER_RIGHT = "{{ $data->border_right }}";
        const BORDER_LEFT = "{{ $data->border_left }}";

        console.log(BORDER_TOP)
        console.log(BORDER_BOTTOM)
        console.log(BORDER_RIGHT)
        console.log(BORDER_LEFT)
        let PHOTO_SLOTS = [];

        const video = document.getElementById('video');
        const captureBtn = document.getElementById('capture-btn');
        const resultCanvas = document.getElementById('result-canvas');
        const statusText = document.getElementById('status-text');
        const countdownElem = document.getElementById('countdown');
        const downloadLink = document.getElementById('download-link');
        const resultCtx = resultCanvas.getContext('2d');
        const MASK_COLORS = [{
                r: 0,
                g: 255,
                b: 38
            }, // Foto 1 - #00FF26
            {
                r: 0,
                g: 102,
                b: 255
            }, // Foto 2 - #0066FF
            {
                r: 255,
                g: 136,
                b: 0
            } // Foto 3 - #FF8800
        ];

        let currentShot = 1;
        const frameImage = new Image();
        frameImage.crossOrigin = "Anonymous";

        function setupCanvas() {
            resultCanvas.width = frameImage.width;
            resultCanvas.height = frameImage.height;
            resultCanvas.style.height = `${(resultCanvas.height / resultCanvas.width) * 100}%`;
            resultCtx.drawImage(frameImage, 0, 0);
            updateUI();
        }

        function updateUI() {
            if (currentShot <= MASK_COLORS.length) {
                statusText.textContent = `Siap untuk foto ke-${currentShot}`;
                captureBtn.textContent = `Ambil Foto ${currentShot}/${MASK_COLORS.length}`;
                captureBtn.disabled = false;
            } else {
                statusText.textContent = 'Sesi foto selesai!';
                captureBtn.textContent = 'Mulai Lagi';
                captureBtn.disabled = false;
                downloadLink.style.display = 'block';
                downloadLink.href = resultCanvas.toDataURL('image/png');
            }
        }


        function resetCanvas() {
            currentShot = 1;
            resultCtx.drawImage(frameImage, 0, 0);
            updateUI();
            downloadLink.style.display = 'none';
        }

        function detectGreenBoxesFromFrame() {
            const tempCanvas = document.createElement('canvas');
            const tempCtx = tempCanvas.getContext('2d');
            tempCanvas.width = frameImage.width;
            tempCanvas.height = frameImage.height;
            tempCtx.drawImage(frameImage, 0, 0);

            const imgData = tempCtx.getImageData(0, 0, tempCanvas.width, tempCanvas.height);
            const data = imgData.data;

            const greenBoxes = [];
            const visited = new Uint8Array(tempCanvas.width * tempCanvas.height);

            function getIndex(x, y) {
                return (y * tempCanvas.width + x);
            }

            function isGreen(r, g, b) {
                return r === 0 && g === 255 && b === 38;
            }

            function floodFill(x, y) {
                const queue = [
                    [x, y]
                ];
                let minX = x,
                    maxX = x,
                    minY = y,
                    maxY = y;

                while (queue.length) {
                    const [cx, cy] = queue.pop();
                    const idx = getIndex(cx, cy);
                    if (visited[idx]) continue;
                    visited[idx] = 1;

                    const i = idx * 4;
                    const r = data[i],
                        g = data[i + 1],
                        b = data[i + 2];
                    if (!isGreen(r, g, b)) continue;

                    minX = Math.min(minX, cx);
                    maxX = Math.max(maxX, cx);
                    minY = Math.min(minY, cy);
                    maxY = Math.max(maxY, cy);

                    const neighbors = [
                        [cx + 1, cy],
                        [cx - 1, cy],
                        [cx, cy + 1],
                        [cx, cy - 1]
                    ];
                    for (const [nx, ny] of neighbors) {
                        if (nx >= 0 && ny >= 0 && nx < tempCanvas.width && ny < tempCanvas.height) {
                            queue.push([nx, ny]);
                        }
                    }
                }

                if ((maxX - minX > 20) && (maxY - minY > 20)) {
                    greenBoxes.push({
                        x: minX,
                        y: minY,
                        width: maxX - minX,
                        height: maxY - minY
                    });
                }
            }

            for (let y = 0; y < tempCanvas.height; y++) {
                for (let x = 0; x < tempCanvas.width; x++) {
                    const idx = getIndex(x, y);
                    if (visited[idx]) continue;
                    const i = idx * 4;
                    const r = data[i],
                        g = data[i + 1],
                        b = data[i + 2];
                    if (isGreen(r, g, b)) {
                        floodFill(x, y);
                    }
                }
            }

            greenBoxes.sort((a, b) => a.y - b.y);
            PHOTO_SLOTS = greenBoxes;
        }

        function takeAndPlacePicture() {
            if (currentShot > MASK_COLORS.length) {
                resetCanvas();
                return;
            }

            captureBtn.disabled = true;
            let count = 3;
            countdownElem.textContent = count;

            const timer = setInterval(() => {
                count--;
                if (count > 0) {
                    countdownElem.textContent = count;
                } else if (count === 0) {
                    countdownElem.textContent = "SMILE!";
                } else {
                    clearInterval(timer);
                    countdownElem.textContent = "";

                    const targetColor = MASK_COLORS[currentShot - 1];
                    const frameImageData = resultCtx.getImageData(0, 0, resultCanvas.width, resultCanvas.height);
                    const fData = frameImageData.data;

                    let minX = resultCanvas.width,
                        minY = resultCanvas.height;
                    let maxX = 0,
                        maxY = 0;

                    for (let y = 0; y < resultCanvas.height; y++) {
                        for (let x = 0; x < resultCanvas.width; x++) {
                            const i = (y * resultCanvas.width + x) * 4;
                            const r = fData[i],
                                g = fData[i + 1],
                                b = fData[i + 2];

                            if (
                                Math.abs(r - targetColor.r) < 10 &&
                                Math.abs(g - targetColor.g) < 10 &&
                                Math.abs(b - targetColor.b) < 10
                            ) {
                                if (x < minX) minX = x;
                                if (x > maxX) maxX = x;
                                if (y < minY) minY = y;
                                if (y > maxY) maxY = y;
                            }
                        }
                    }

                    const PADDING = 5;
                    const maskX = Math.max(minX - PADDING, 0);
                    const maskY = Math.max(minY - PADDING, 0);
                    const maskWidth = Math.min((maxX - minX) + PADDING * 2, resultCanvas.width - maskX);
                    const maskHeight = Math.min((maxY - minY) + PADDING * 2, resultCanvas.height - maskY);

                    if (maskWidth > 0 && maskHeight > 0) {
                        const camWidth = video.videoWidth;
                        const camHeight = video.videoHeight;

                        const maskRatio = maskWidth / maskHeight;
                        const camRatio = camWidth / camHeight;

                        let sx, sy, sWidth, sHeight;
                        if (camRatio > maskRatio) {
                            sHeight = camHeight;
                            sWidth = camHeight * maskRatio;
                            sx = (camWidth - sWidth) / 2;
                            sy = 0;
                        } else {
                            sWidth = camWidth;
                            sHeight = camWidth / maskRatio;
                            sx = 0;
                            sy = (camHeight - sHeight) / 2;
                        }

                        const cornerRadii = {
                            tl: 0,
                            tr: 40,
                            br: 0,
                            bl: 40
                        };

                        resultCtx.save();
                        resultCtx.imageSmoothingEnabled = true;
                        resultCtx.imageSmoothingQuality = "high";

                        drawRoundedRectPath(resultCtx, maskX, maskY, maskWidth, maskHeight, cornerRadii);
                        resultCtx.clip();

                        resultCtx.scale(-1, 1);
                        resultCtx.drawImage(
                            video,
                            sx, sy, sWidth, sHeight,
                            -maskX - maskWidth, maskY,
                            maskWidth, maskHeight
                        );
                        resultCtx.restore();
                    }

                    currentShot++;
                    updateUI();
                }
            }, 1000);
        }

        function drawRoundedRectPath(ctx, x, y, width, height, radii) {
            const {
                tl,
                tr,
                br,
                bl
            } = radii;
            ctx.beginPath();
            ctx.moveTo(x + tl, y);
            ctx.lineTo(x + width - tr, y);
            ctx.quadraticCurveTo(x + width, y, x + width, y + tr);
            ctx.lineTo(x + width, y + height - br);
            ctx.quadraticCurveTo(x + width, y + height, x + width - br, y + height);
            ctx.lineTo(x + bl, y + height);
            ctx.quadraticCurveTo(x, y + height, x, y + height - bl);
            ctx.lineTo(x, y + tl);
            ctx.quadraticCurveTo(x, y, x + tl, y);
            ctx.closePath();
        }

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: 1280,
                        height: 720
                    }
                });
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    frameImage.src = FRAME_URL;
                };
            } catch (err) {
                statusText.textContent = "Error: Gagal akses kamera!";
                alert("Kamera tidak ditemukan atau izin ditolak. Mohon periksa kembali.");
            }
        }

        frameImage.onload = () => {
            detectGreenBoxesFromFrame();
            setupCanvas();
        };

        frameImage.onerror = () => {
            statusText.textContent = 'Gagal memuat bingkai!';
            alert(
                'Gagal memuat gambar frame. Pastikan URL benar dan server Anda sudah berjalan serta mengizinkan CORS.'
            );
        };

        captureBtn.addEventListener('click', takeAndPlacePicture);
        window.addEventListener('load', startCamera);
    </script>
</body>

</html>

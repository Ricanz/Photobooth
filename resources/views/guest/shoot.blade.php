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
        const FRAME_URL = 'http://127.0.0.1:8000/storage/Frames/trj1sYlhFvSICAifgxMmk6WeEUjYxgorY0Le2Cyj.png';
        let PHOTO_SLOTS = [];

        const video = document.getElementById('video');
        const captureBtn = document.getElementById('capture-btn');
        const resultCanvas = document.getElementById('result-canvas');
        const statusText = document.getElementById('status-text');
        const countdownElem = document.getElementById('countdown');
        const downloadLink = document.getElementById('download-link');
        const resultCtx = resultCanvas.getContext('2d');

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
            if (currentShot <= PHOTO_SLOTS.length) {
                statusText.textContent = `Siap untuk foto ke-${currentShot}`;
                captureBtn.textContent = `Ambil Foto ${currentShot}/${PHOTO_SLOTS.length}`;
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
                const queue = [[x, y]];
                let minX = x, maxX = x, minY = y, maxY = y;

                while (queue.length) {
                    const [cx, cy] = queue.pop();
                    const idx = getIndex(cx, cy);
                    if (visited[idx]) continue;
                    visited[idx] = 1;

                    const i = idx * 4;
                    const r = data[i], g = data[i + 1], b = data[i + 2];
                    if (!isGreen(r, g, b)) continue;

                    minX = Math.min(minX, cx);
                    maxX = Math.max(maxX, cx);
                    minY = Math.min(minY, cy);
                    maxY = Math.max(maxY, cy);

                    const neighbors = [
                        [cx + 1, cy], [cx - 1, cy],
                        [cx, cy + 1], [cx, cy - 1]
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
                    const r = data[i], g = data[i + 1], b = data[i + 2];
                    if (isGreen(r, g, b)) {
                        floodFill(x, y);
                    }
                }
            }

            greenBoxes.sort((a, b) => a.y - b.y); // Urutkan dari atas ke bawah
            PHOTO_SLOTS = greenBoxes;
        }

        function takeAndPlacePicture() {
            if (currentShot > PHOTO_SLOTS.length) {
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

                    const slot = PHOTO_SLOTS[currentShot - 1];

                    resultCtx.save();
                    resultCtx.beginPath();
                    resultCtx.rect(slot.x, slot.y, slot.width, slot.height);
                    resultCtx.clip();
                    resultCtx.scale(-1, 1);
                    resultCtx.drawImage(video, -(slot.x + slot.width), slot.y, slot.width, slot.height);
                    resultCtx.restore();

                    currentShot++;
                    updateUI();
                }
            }, 1000);
        }

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 1280, height: 720 }
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
            alert('Gagal memuat gambar frame. Pastikan URL benar dan server Anda sudah berjalan serta mengizinkan CORS.');
        };

        captureBtn.addEventListener('click', takeAndPlacePicture);
        window.addEventListener('load', startCamera);
    </script>
</body>

</html>
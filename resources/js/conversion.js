document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-input');
    const progressBar = document.getElementById('progress-bar');
    const progressPercent = document.getElementById('progress-percent');
    const errorMsg = document.getElementById('error-msg');
    const downloadLink = document.getElementById('download-link');

    // Helper to reset UI
    function resetUI() {
        progressBar.classList.add('hidden');
        errorMsg.classList.add('hidden');
        downloadLink.classList.add('hidden');
        progressPercent.textContent = '';
    }

    // Show error message
    function showError(message) {
        errorMsg.textContent = message;
        errorMsg.classList.remove('hidden');
    }

    // Handle file selection
    function handleFile(file) {
        resetUI();
        const formData = new FormData();
        formData.append('file', file);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/conversion/upload', true);

        // Set CSRF token header if available
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfMeta.content);
        }

        // Upload progress
        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.classList.remove('hidden');
                progressPercent.textContent = `${percent}%`;
                progressPercent.style.width = `${percent}%`;
            }
        });

        xhr.addEventListener('load', function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    downloadLink.href = response.download_url;
                    downloadLink.classList.remove('hidden');
                } else {
                    showError(response.message || 'Unknown error during conversion.');
                }
            } else {
                let errMsg = 'Upload failed.';
                try {
                    const resp = JSON.parse(xhr.responseText);
                    errMsg = resp.message || errMsg;
                } catch (e) {}
                showError(errMsg);
            }
        });

        xhr.addEventListener('error', function () {
            showError('Network error during file upload.');
        });

        xhr.send(formData);
    }

    // Click on drop area triggers file dialog
    dropArea.addEventListener('click', function () {
        fileInput.click();
    });

    // File selected via dialog
    fileInput.addEventListener('change', function (e) {
        if (e.target.files && e.target.files[0]) {
            handleFile(e.target.files[0]);
        }
    });

    // Drag & drop handlers
    ['dragenter', 'dragover'].forEach(event => {
        dropArea.addEventListener(event, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(event => {
        dropArea.addEventListener(event, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('dragover');
        });
    });

    dropArea.addEventListener('drop', function (e) {
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            handleFile(e.dataTransfer.files[0]);
        }
    });
});
/**
 * Conversion workflow script
 * Handles file upload, polling for status, progress bar updates, and download link rendering.
 */
import axios from 'axios';

(function () {
    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const progressBar = document.getElementById('progressBar');
    const progressEl = document.getElementById('progress');
    const progressText = document.getElementById('progressText');
    const downloadLink = document.getElementById('downloadLink');

    let conversionId = null;
    let pollingInterval = null;

    /**
     * Utility to set CSRF token header for axios requests
     */
    const setAxiosCsrf = () => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        }
    };

    /**
     * Upload the selected file to the server
     */
    const uploadFile = async () => {
        const file = fileInput.files[0];
        if (!file) {
            alert('Please select a file to upload.');
            return;
        }

        // Disable UI elements during upload
        uploadBtn.disabled = true;
        fileInput.disabled = true;

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await axios.post('/conversion/upload', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
                onUploadProgress: (progressEvent) => {
                    const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressEl.value = percentCompleted;
                    progressText.textContent = `${percentCompleted}% (Uploading)`;
                }
            });

            conversionId = response.data.id;
            if (!conversionId) {
                throw new Error('No conversion ID returned from server.');
            }

            // Show the progress bar and reset progress
            progressBar.hidden = false;
            progressEl.value = 0;
            progressText.textContent = '0% (Queued)';

            // Start polling for status updates
            pollingInterval = setInterval(pollStatus, 2000);
        } catch (error) {
            console.error('Upload failed:', error);
            alert('Upload failed. Please try again.');
            uploadBtn.disabled = false;
            fileInput.disabled = false;
        }
    };

    /**
     * Poll the conversion status endpoint
     */
    const pollStatus = async () => {
        try {
            const response = await axios.get(`/conversion/${conversionId}/status`);
            const { status, progress } = response.data;

            progressEl.value = progress;
            progressText.textContent = `${progress}% (${status})`;

            if (status === 'completed') {
                clearInterval(pollingInterval);
                downloadLink.href = `/conversion/${conversionId}/download`;
                downloadLink.hidden = false;
            } else if (status === 'failed') {
                clearInterval(pollingInterval);
                alert('Conversion failed. Please try again.');
                uploadBtn.disabled = false;
                fileInput.disabled = false;
            }
        } catch (error) {
            console.error('Status polling error:', error);
        }
    };

    setAxiosCsrf();

    uploadBtn.addEventListener('click', uploadFile);
})();
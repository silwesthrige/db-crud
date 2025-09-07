@extends('templates.admin-master')
@section('header_content')
<title>Import Events</title>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Import Events from CSV
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>CSV Format Requirements:</strong>
                        <ul class="mb-0 mt-2">
                            <li>First row should contain headers</li>
                            <li>Required columns: ID, Event Name, Description, Priority, Event Date</li>
                            <li>Priority values: High, Medium, or Low</li>
                            <li>Date format: YYYY-MM-DD (e.g., 2025-09-07)</li>
                            <li>Maximum file size: 10MB</li>
                        </ul>
                    </div>

                    <form action="{{ url('/events/import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="csv_file" class="form-label fw-semibold">
                                <i class="fas fa-file-csv me-1"></i>Choose CSV File
                            </label>
                            <input type="file" class="form-control @error('csv_file') is-invalid @enderror" 
                                   id="csv_file" name="csv_file" accept=".csv,.txt" required>
                            @error('csv_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                Only CSV files are allowed (max 10MB)
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="card border-light bg-light">
                                <div class="card-body py-3">
                                    <h6 class="card-title">
                                        <i class="fas fa-table me-1"></i>Sample CSV Format:
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Event Name</th>
                                                    <th>Description</th>
                                                    <th>Priority</th>
                                                    <th>Event Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Annual Conference</td>
                                                    <td>Company annual meeting</td>
                                                    <td>High</td>
                                                    <td>2025-12-15</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Team Building</td>
                                                    <td>Quarterly team activity</td>
                                                    <td>Medium</td>
                                                    <td>2025-10-20</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="importBtn">
                                <i class="fas fa-upload me-2"></i>Import Events
                            </button>
                            <a href="{{ url('/events') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Events
                            </a>
                            <a href="{{ url('/events/export') }}" class="btn btn-outline-success">
                                <i class="fas fa-download me-2"></i>Download Sample CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- File Upload Progress -->
            <div class="card mt-4 d-none" id="uploadProgress">
                <div class="card-body">
                    <h6><i class="fas fa-cog fa-spin me-2"></i>Processing Import...</h6>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" id="progressBar">
                        </div>
                    </div>
                    <small class="text-muted mt-2 d-block">Please wait while we process your file...</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('optional_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const csvFileInput = document.getElementById('csv_file');

    // File validation
    csvFileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size (10MB = 10 * 1024 * 1024 bytes)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                this.value = '';
                return;
            }

            // Check file type
            const allowedTypes = ['text/csv', 'application/csv', 'text/plain'];
            if (!allowedTypes.includes(file.type) && !file.name.endsWith('.csv')) {
                alert('Please select a valid CSV file');
                this.value = '';
                return;
            }

            // Preview file info
            const fileInfo = document.getElementById('fileInfo');
            if (fileInfo) {
                fileInfo.innerHTML = `
                    <small class="text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                    </small>
                `;
            }
        }
    });

    // Form submission with progress
    importForm.addEventListener('submit', function(e) {
        const file = csvFileInput.files[0];
        if (!file) {
            e.preventDefault();
            alert('Please select a CSV file');
            return;
        }

        // Show progress
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importing...';
        uploadProgress.classList.remove('d-none');

        // Simulate progress (since we can't track actual server progress easily)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            
            progressBar.style.width = progress + '%';
            progressBar.textContent = Math.round(progress) + '%';
        }, 500);

        // The form will submit normally, so clear interval after a delay
        setTimeout(() => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';
        }, 3000);
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('.card-body');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-primary', 'bg-light');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-primary', 'bg-light');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            csvFileInput.files = files;
            csvFileInput.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endsection

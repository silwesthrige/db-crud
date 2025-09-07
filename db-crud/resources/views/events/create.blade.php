@extends('templates.admin-master')
@section('header_content')
<title>Create New Event</title>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>Create New Event
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('/events/create') }}" method="POST" id="createEventForm" novalidate>
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="eventName" class="form-label fw-semibold">
                                        Event Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           id="eventName" 
                                           placeholder="Enter event name" 
                                           value="{{ old('name') }}"
                                           required 
                                           maxlength="100"
                                           data-validation="required|min:3|max:100">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                    <div class="form-text">
                                        <span class="char-count">0</span>/100 characters
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="eventDate" class="form-label fw-semibold">
                                        Event Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('event_date') is-invalid @enderror" 
                                           name="event_date" 
                                           id="eventDate" 
                                           value="{{ old('event_date') }}"
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           data-validation="required|future_date">
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                    <div class="form-text">
                                        Event date must be today or in the future
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="eventDescription" class="form-label fw-semibold">
                                Event Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" 
                                      id="eventDescription" 
                                      rows="4" 
                                      placeholder="Describe your event..."
                                      maxlength="500"
                                      data-validation="max:500">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback"></div>
                            @enderror
                            <div class="form-text">
                                <span class="char-count">0</span>/500 characters (optional)
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="eventPriority" class="form-label fw-semibold">
                                Priority Level <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    name="priority" 
                                    id="eventPriority" 
                                    required
                                    data-validation="required">
                                <option value="">Select Priority Level</option>
                                <option value="High" {{ old('priority') == 'High' ? 'selected' : '' }}>
                                    High
                                </option>
                                <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>
                                    Medium
                                </option>
                                <option value="Low" {{ old('priority') == 'Low' ? 'selected' : '' }}>
                                    Low
                                </option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback"></div>
                            @enderror
                        </div>

                        <!-- Form Summary -->
                        <div class="card border-light bg-light mb-4 d-none" id="formSummary">
                            <div class="card-body py-2">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-eye me-1"></i>Preview:
                                </h6>
                                <div id="summaryContent"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2 align-items-center">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Create Event
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="window.location='{{ url('events') }}'">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-outline-info" id="previewBtn">
                                <i class="fas fa-eye me-2"></i>Preview
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('optional_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createEventForm');
    const submitBtn = document.getElementById('submitBtn');
    const previewBtn = document.getElementById('previewBtn');
    const resetBtn = document.getElementById('resetBtn');
    const formSummary = document.getElementById('formSummary');
    const summaryContent = document.getElementById('summaryContent');
    
    // Form fields
    const eventName = document.getElementById('eventName');
    const eventDate = document.getElementById('eventDate');
    const eventDescription = document.getElementById('eventDescription');
    const eventPriority = document.getElementById('eventPriority');

    // Real-time validation
    const validators = {
        required: (value) => value.trim() !== '',
        min: (value, min) => value.trim().length >= min,
        max: (value, max) => value.trim().length <= max,
        future_date: (value) => {
            if (!value) return false;
            const selectedDate = new Date(value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return selectedDate >= today;
        }
    };

    function validateField(field) {
        const validationRules = field.dataset.validation;
        if (!validationRules) return true;

        const rules = validationRules.split('|');
        const value = field.value;
        let isValid = true;
        let errorMessage = '';

        for (const rule of rules) {
            const [ruleName, ruleValue] = rule.split(':');
            
            switch (ruleName) {
                case 'required':
                    if (!validators.required(value)) {
                        isValid = false;
                        errorMessage = 'This field is required';
                    }
                    break;
                case 'min':
                    if (value && !validators.min(value, parseInt(ruleValue))) {
                        isValid = false;
                        errorMessage = `Minimum ${ruleValue} characters required`;
                    }
                    break;
                case 'max':
                    if (!validators.max(value, parseInt(ruleValue))) {
                        isValid = false;
                        errorMessage = `Maximum ${ruleValue} characters allowed`;
                    }
                    break;
                case 'future_date':
                    if (value && !validators.future_date(value)) {
                        isValid = false;
                        errorMessage = 'Date must be today or in the future';
                    }
                    break;
            }
            
            if (!isValid) break;
        }

        // Update field appearance
        const invalidFeedback = field.parentNode.querySelector('.invalid-feedback');
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            if (invalidFeedback) invalidFeedback.textContent = '';
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            if (invalidFeedback) invalidFeedback.textContent = errorMessage;
        }

        return isValid;
    }

    // Character counting
    function updateCharCount(field) {
        const maxLength = parseInt(field.getAttribute('maxlength')) || 0;
        if (maxLength > 0) {
            const charCountSpan = field.parentNode.querySelector('.char-count');
            if (charCountSpan) {
                const currentLength = field.value.length;
                charCountSpan.textContent = currentLength;
                
                // Update color based on usage
                const percentage = (currentLength / maxLength) * 100;
                if (percentage > 90) {
                    charCountSpan.className = 'char-count text-danger';
                } else if (percentage > 70) {
                    charCountSpan.className = 'char-count text-warning';
                } else {
                    charCountSpan.className = 'char-count text-muted';
                }
            }
        }
    }

    // Add event listeners for real-time validation
    [eventName, eventDate, eventDescription, eventPriority].forEach(field => {
        if (field) {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                updateCharCount(field);
                // Validate after a short delay to avoid constant validation
                clearTimeout(field.validationTimeout);
                field.validationTimeout = setTimeout(() => validateField(field), 500);
            });
        }
    });

    // Initialize character counts
    [eventName, eventDescription].forEach(field => {
        if (field) updateCharCount(field);
    });

    // Preview functionality
    previewBtn.addEventListener('click', function() {
        const name = eventName.value.trim();
        const date = eventDate.value;
        const description = eventDescription.value.trim();
        const priority = eventPriority.value;

        if (!name || !date || !priority) {
            alert('Please fill in all required fields before previewing');
            return;
        }

        const priorityIcon = {
            'High': '🔴',
            'Medium': '🟡',
            'Low': '🟢'
        };

        const formattedDate = date ? new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : '';

        summaryContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>Event Name:</strong><br>
                    <span class="text-primary">${name}</span>
                </div>
                <div class="col-md-6">
                    <strong>Date:</strong><br>
                    <span class="text-info">${formattedDate}</span>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <strong>Priority:</strong><br>
                    <span class="text-secondary">${priority}</span>
                </div>
                <div class="col-md-6">
                    <strong>Description:</strong><br>
                    <span class="text-muted">${description || 'No description provided'}</span>
                </div>
            </div>
        `;

        formSummary.classList.remove('d-none');
        formSummary.scrollIntoView({ behavior: 'smooth' });
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isFormValid = true;

        [eventName, eventDate, eventPriority].forEach(field => {
            if (!validateField(field)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            e.preventDefault();
            
            // Focus on first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Show error message
            showToast('error', 'Validation Error', 'Please fix the errors before submitting');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Event...';
    });

    // Reset form
    resetBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
            form.reset();
            // Clear validation states
            form.querySelectorAll('.is-valid, .is-invalid').forEach(field => {
                field.classList.remove('is-valid', 'is-invalid');
            });
            // Reset character counts
            [eventName, eventDescription].forEach(field => {
                if (field) updateCharCount(field);
            });
            // Hide preview
            formSummary.classList.add('d-none');
        }
    });

    // Toast notification function
    function showToast(type, title, message) {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white border-0 ${type === 'error' ? 'bg-danger' : 'bg-success'}`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    // Auto-save draft (optional enhancement)
    let draftSaveTimeout;
    function saveDraft() {
        const draftData = {
            name: eventName.value,
            date: eventDate.value,
            description: eventDescription.value,
            priority: eventPriority.value,
            timestamp: new Date().toISOString()
        };
        localStorage.setItem('event_draft', JSON.stringify(draftData));
    }

    // Load draft if exists
    function loadDraft() {
        const draft = localStorage.getItem('event_draft');
        if (draft) {
            try {
                const draftData = JSON.parse(draft);
                // Only load if draft is less than 1 hour old
                const draftAge = new Date() - new Date(draftData.timestamp);
                if (draftAge < 3600000) { // 1 hour in milliseconds
                    if (confirm('Found a saved draft. Would you like to restore it?')) {
                        eventName.value = draftData.name || '';
                        eventDate.value = draftData.date || '';
                        eventDescription.value = draftData.description || '';
                        eventPriority.value = draftData.priority || '';
                        
                        // Update character counts
                        [eventName, eventDescription].forEach(field => {
                            if (field) updateCharCount(field);
                        });
                    }
                }
            } catch (e) {
                // Invalid draft data, remove it
                localStorage.removeItem('event_draft');
            }
        }
    }

    // Save draft on input changes
    [eventName, eventDate, eventDescription, eventPriority].forEach(field => {
        if (field) {
            field.addEventListener('input', function() {
                clearTimeout(draftSaveTimeout);
                draftSaveTimeout = setTimeout(saveDraft, 2000); // Save after 2 seconds of inactivity
            });
        }
    });

    // Load draft on page load
    loadDraft();

    // Clear draft on successful submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('event_draft');
    });
});
</script>
@endsection
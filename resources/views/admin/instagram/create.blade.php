@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="font-weight-bold mb-0 animate__animated animate__fadeInDown">
                    <i class="fab fa-instagram text-instagram mr-2"></i>Create Instagram Promotion
                </h2>
                <hr class="mt-2 mb-4" style="border-top: 2px solid #e1306c; opacity: 0.2;">
            </div>
        </div>

        <div class="row justify-content-center animate__animated animate__fadeIn">
            <div class="col-lg-8">
                <form id="instagramPostForm" enctype="multipart/form-data" class="bg-white p-4 rounded-lg shadow-sm">
                    @csrf

                    <!-- Image Upload Section -->
                    <div class="form-section mb-5">
                        <h5 class="section-title font-weight-bold text-muted mb-4">
                            <span class="number-circle">1</span> Upload Image
                        </h5>

                        <div class="dropzone-wrapper">
                            <div class="dropzone-container text-center py-5 px-3">
                                <input type="file" class="dropzone-input" id="image" name="image" required>
                                <div class="dropzone-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="mb-2">Drag & drop your image here</h5>
                                    <p class="text-muted mb-3">or click to browse files</p>
                                    <div class="file-info text-muted small"></div>
                                </div>
                            </div>
                            <div class="preview-container mt-4 text-center">
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded shadow d-none"
                                    style="max-height: 400px;">
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="form-section mb-5">
                        <h5 class="section-title font-weight-bold text-muted mb-4">
                            <span class="number-circle">2</span> Post Description
                        </h5>

                        <div class="form-group mb-0">
                            <textarea class="form-control border-2" id="description" name="description" rows="6"
                                placeholder="Write your Instagram caption here..." required></textarea>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-hashtag mr-1"></i> Use relevant hashtags for better reach
                                </small>
                                <small class="text-muted font-weight-bold">
                                    <span id="charCount">0</span>/2200
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group text-center mt-5">
                        <button type="submit"
                            class="btn btn-instagram-gradient btn-lg px-5 py-3 animate__animated animate__pulse animate__infinite">
                            <i class="fab fa-instagram mr-2"></i> Publish to Instagram
                        </button>
                    </div>
                </form>

                <!-- Result Container -->
                <div id="resultContainer" class="mt-5 d-none animate__animated animate__fadeInUp">
                    <div class="success-card bg-white p-4 rounded-lg shadow-sm border-left-4 border-success">
                        <div class="d-flex align-items-center">
                            <div class="success-icon mr-3">
                                <i class="fas fa-check-circle fa-3x text-success"></i>
                            </div>
                            <div>
                                <h4 class="font-weight-bold mb-1">Post Published Successfully!</h4>
                                <p id="successMessage" class="mb-2 text-muted"></p>
                                <a id="postLink" href="#" target="_blank" class="btn btn-sm btn-outline-success">
                                    View Post <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --instagram-gradient: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D);
            --instagram-pink: #E1306C;
        }

        .text-instagram {
            color: #E1306C;
        }

        .btn-instagram-gradient {
            background: var(--instagram-gradient);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(224, 48, 108, 0.3);
        }

        .btn-instagram-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(224, 48, 108, 0.4);
            color: white;
        }

        .btn-instagram-gradient:active {
            transform: translateY(1px);
        }

        .form-section {
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .section-title {
            position: relative;
            padding-left: 40px;
            color: #495057;
        }

        .number-circle {
            position: absolute;
            left: 0;
            top: -5px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--instagram-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        .dropzone-wrapper {
            position: relative;
        }

        .dropzone-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dropzone-container:hover {
            border-color: var(--instagram-pink);
            background: rgba(224, 48, 108, 0.03);
        }

        .dropzone-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            top: 0;
            left: 0;
            cursor: pointer;
        }

        .border-left-4 {
            border-left: 4px solid !important;
        }

        .success-card {
            transition: all 0.4s ease;
        }

        .success-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            animation: bounceIn 0.6s ease;
        }

        /* Character count color */
        #charCount {
            transition: color 0.3s ease;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Image preview with dropzone
            const dropzone = $('.dropzone-container');
            const fileInput = $('#image');
            const fileInfo = $('.file-info');

            // Highlight dropzone when dragging over
            dropzone.on('dragover', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': '#E1306C',
                    'background-color': 'rgba(224, 48, 108, 0.05)'
                });
            });

            // Remove highlight when dragging leaves
            dropzone.on('dragleave', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': '#dee2e6',
                    'background-color': 'white'
                });
            });

            // Handle dropped files
            dropzone.on('drop', function(e) {
                e.preventDefault();
                $(this).css({
                    'border-color': '#dee2e6',
                    'background-color': 'white'
                });

                const file = e.originalEvent.dataTransfer.files[0];
                if (file) {
                    handleFileSelection(file);
                }
            });

            // Handle clicked files
            fileInput.change(function() {
                if (this.files && this.files[0]) {
                    handleFileSelection(this.files[0]);
                }
            });

            function handleFileSelection(file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (JPEG, PNG, JPG, GIF)');
                    return;
                }

                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }

                // Update file info
                fileInfo.html(
                    `<i class="fas fa-file-image mr-1"></i> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)}MB)`
                );

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
                    dropzone.find('.dropzone-content').addClass('d-none');
                }
                reader.readAsDataURL(file);
            }

            // Character count
            $('#description').keyup(function() {
                const count = $(this).val().length;
                const charCount = $('#charCount');
                charCount.text(count);

                // Change color if approaching limit
                if (count > 2000) {
                    charCount.css('color', '#dc3545');
                } else if (count > 1800) {
                    charCount.css('color', '#fd7e14');
                } else {
                    charCount.css('color', '#28a745');
                }
            });

            // Form submission
            // Form submission
            $('#instagramPostForm').submit(function(e) {
                e.preventDefault();

                const form = $(this);
                const button = form.find('button[type="submit"]');

                // Add loading state
                button.html('<i class="fas fa-spinner fa-spin mr-2"></i> Publishing...');
                button.addClass('disabled');

                // Create FormData
                let formData = new FormData(this);

                // Submit via AJAX
                $.ajax({
                    url: "{{ route('admin.instagram.post') }}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show success message
                            $('#successMessage').text('"' + response.post + '"');
                            $('#postLink').attr('href', response.postIds[0].postUrl);

                            // Animate success card
                            $('#resultContainer').removeClass('d-none')
                                .hide()
                                .fadeIn(500);

                            // Reset form
                            form.trigger('reset');
                            $('#imagePreview').addClass('d-none');
                            $('.file-info').empty();
                            $('.dropzone-content').removeClass('d-none');
                            $('#charCount').text('0').css('color', '#28a745');
                        } else {
                            showError(response.errors[0] || 'Posting failed');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = xhr.responseJSON.errors[0];
                        }
                        showError(errorMessage);
                    },
                    complete: function() {
                        button.html(
                            '<i class="fab fa-instagram mr-2"></i> Publish to Instagram');
                        button.removeClass('disabled');
                    }
                });
            });

            function showError(message) {
                const errorAlert = $(`
        <div class="alert alert-danger animate__animated animate__shakeX">
            <i class="fas fa-exclamation-circle mr-2"></i> ${message}
        </div>
    `).insertBefore('#instagramPostForm');

                setTimeout(() => {
                    errorAlert.fadeOut(500, () => errorAlert.remove());
                }, 5000);
            }
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Edit Homepage - Admin')

@push('styles')
    <style>
        .admin-layout {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
            flex-shrink: 0;
            position: sticky;
            top: 20px;
        }

        .sidebar h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #fff;
            font-size: 18px;
            border-bottom: 2px solid #495057;
            padding-bottom: 15px;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            color: #333;
            text-decoration: none;
            border: none;
            font-size: 14px;
            font-weight: 500;
            background: #e9ecef;
            text-align: center;
        }

        .sidebar .menu-item:hover {
            background: #007bff;
            color: white;
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .sidebar .menu-item.active {
            background: #007bff;
            color: white;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 900px;
        }

        .form-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .form-header h2 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .form-header p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .form-section h3 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .facility-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
        }

        .facility-item-content {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 12px;
            align-items: flex-end;
        }

        .btn-remove {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }

        .btn-remove:hover {
            background: #c82333;
        }

        .btn-add-facility {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-add-facility:hover {
            background: #218838;
        }

        .slider-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 6px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: border-color 0.3s, background 0.3s;
        }

        .slider-upload-area:hover {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .slider-upload-area.dragover {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .slider-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .slider-image-item {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            background: #f0f0f0;
            aspect-ratio: 16/9;
        }

        .slider-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slider-image-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: background 0.3s;
        }

        .slider-image-remove:hover {
            background: #c82333;
        }

        .form-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .emoji-picker-button {
            padding: 8px 12px;
            background: #e9ecef;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
@endpush

<div class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Menu</h3>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">Dashboard</a>
            <a href="{{ route('admin.villas.index') }}" class="menu-item">Manage Villas</a>
            <a href="{{ route('admin.reservations') }}" class="menu-item">Reservations</a>
            <a href="{{ route('admin.users.index') }}" class="menu-item">Users</a>
            <a href="{{ route('admin.homepage.edit') }}" class="menu-item active">Edit Homepage</a>
            <a href="{{ route('admin.finances') }}" class="menu-item">Finances</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h2>Edit Homepage</h2>
                <p>Customize the homepage content, image slider, and facilities</p>
            </div>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 10px 0 0 20px; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.homepage.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Description Section -->
                <div class="form-section">
                    <h3>Homepage Description</h3>
                    <div class="form-group">
                        <label for="description">Main Description</label>
                        <textarea id="description" name="description"
                            placeholder="Enter main description for homepage">{{ $homepageData['description'] ?? '' }}</textarea>
                        <p style="color: #666; font-size: 12px; margin-top: 8px;">This text will appear on the homepage
                            hero section</p>
                    </div>
                </div>

                <!-- Image Slider Section -->
                <div class="form-section">
                    <h3>Image Slider</h3>
                    <p style="color: #666; font-size: 13px; margin-bottom: 15px;">Add images that will be displayed in
                        the homepage slider</p>

                    <div class="slider-upload-area" id="dropZone"
                        onclick="document.getElementById('sliderImages').click()">
                        <p style="margin: 0 0 10px 0; font-size: 16px;">📸 Drag and drop images or click to select</p>
                        <p style="margin: 0; color: #666; font-size: 12px;">JPG, PNG, GIF (Max 5MB each)</p>
                    </div>
                    <input type="file" id="sliderImages" name="slider_images[]" multiple accept="image/*"
                        style="display: none;">

                    <div class="slider-images" id="sliderImagesList">
                        @if(isset($homepageData['sliders']) && count($homepageData['sliders']) > 0)
                            @foreach($homepageData['sliders'] as $index => $slider)
                                <div class="slider-image-item">
                                    <img src="{{ $slider }}" alt="Slider image">
                                    <button type="button" class="slider-image-remove"
                                        onclick="removeSliderImage(this)">×</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Facilities Section -->
                <div class="form-section">
                    <h3>Homepage Facilities</h3>
                    <p style="color: #666; font-size: 13px; margin-bottom: 15px;">Add facilities/amenities that will be
                        displayed on the homepage</p>

                    <div id="facilitiesList">
                        @if(isset($homepageData['facilities']) && count($homepageData['facilities']) > 0)
                            @foreach($homepageData['facilities'] as $index => $facility)
                                <div class="facility-item">
                                    <div class="facility-item-content">
                                        <div>
                                            <label
                                                style="font-size: 12px; margin-bottom: 4px; display: block; color: #666;">Facility
                                                Name</label>
                                            <input type="text" name="facilities[{{ $index }}][name]"
                                                value="{{ $facility['name'] ?? '' }}" placeholder="e.g., Free WiFi" required>
                                        </div>
                                        <div>
                                            <label
                                                style="font-size: 12px; margin-bottom: 4px; display: block; color: #666;">Icon
                                                (Emoji)</label>
                                            <input type="text" name="facilities[{{ $index }}][icon]"
                                                value="{{ $facility['icon'] ?? '' }}" placeholder="e.g., 📡" maxlength="5">
                                        </div>
                                        <button type="button" class="btn-remove" onclick="removeFacility(this)">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" class="btn-add-facility" onclick="addFacility()">+ Add Facility</button>
                </div>

                <!-- Form Buttons -->
                <div class="form-buttons">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let facilityCount = {{ isset($homepageData['facilities']) ? count($homepageData['facilities']) : 0 }};
    let sliderCount = 0;

    function addFacility() {
        const list = document.getElementById('facilitiesList');
        const item = document.createElement('div');
        item.className = 'facility-item';
        item.innerHTML = `
        <div class="facility-item-content">
            <div>
                <label style="font-size: 12px; margin-bottom: 4px; display: block; color: #666;">Facility Name</label>
                <input type="text" name="facilities[${facilityCount}][name]" placeholder="e.g., Swimming Pool" required>
            </div>
            <div>
                <label style="font-size: 12px; margin-bottom: 4px; display: block; color: #666;">Icon (Emoji)</label>
                <input type="text" name="facilities[${facilityCount}][icon]" placeholder="e.g., 🏊" maxlength="5">
            </div>
            <button type="button" class="btn-remove" onclick="removeFacility(this)">Remove</button>
        </div>
    `;
        list.appendChild(item);
        facilityCount++;
    }

    function removeFacility(button) {
        button.closest('.facility-item').remove();
    }

    // Drag and drop for images
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('sliderImages');

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        fileInput.files = e.dataTransfer.files;
        handleImageSelect();
    });

    fileInput.addEventListener('change', handleImageSelect);

    function handleImageSelect() {
        const files = fileInput.files;
        const list = document.getElementById('sliderImagesList');

        for (let file of files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const item = document.createElement('div');
                    item.className = 'slider-image-item';
                    item.innerHTML = `
                    <img src="${e.target.result}" alt="Slider image">
                    <button type="button" class="slider-image-remove" onclick="removeSliderImage(this)">×</button>
                `;
                    list.appendChild(item);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    function removeSliderImage(button) {
        button.closest('.slider-image-item').remove();
    }
</script>
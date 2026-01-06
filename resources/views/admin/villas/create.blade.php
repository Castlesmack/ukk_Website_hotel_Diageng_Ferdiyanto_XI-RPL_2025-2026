@extends('layouts.app')

@section('title', 'Add Villa - Admin')

@push('styles')
    <style>
        .admin-layout {
            display: flex;
            gap: 20px;
            margin-top: 0;
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
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
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

        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 6px;
            padding: 30px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: border-color 0.3s, background 0.3s;
        }

        .upload-area:hover {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .upload-area.dragover {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .image-item {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            background: #f0f0f0;
            aspect-ratio: 1;
        }

        .image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            transition: background 0.3s;
        }

        .image-remove:hover {
            background: #c82333;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
@endpush

<div class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Admin Menu</h3>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">Dashboard</a>
            <a href="{{ route('admin.villas.index') }}" class="menu-item active">Manage Villas</a>
            <a href="{{ route('admin.reservations') }}" class="menu-item">Reservations</a>
            <a href="{{ route('admin.users.index') }}" class="menu-item">Users</a>
            <a href="{{ route('admin.homepage.edit') }}" class="menu-item">Edit Homepage</a>
            <a href="{{ route('admin.finances') }}" class="menu-item">Finances</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h2>Create New Villa</h2>
                <p>Add a new villa property to your portfolio</p>
            </div>

            @if ($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.villas.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <h3>Villa Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Villa Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., Sunset Paradise Villa" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="base_price">Price per Night (Rp) *</label>
                            <input type="number" id="base_price" name="base_price" step="0.01" value="{{ old('base_price') }}" placeholder="e.g., 5000000" required>
                            @error('base_price')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="capacity">Guest Capacity *</label>
                            <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" placeholder="e.g., 6" required>
                            @error('capacity')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="rooms_total">Number of Bedrooms *</label>
                            <input type="number" id="rooms_total" name="rooms_total" value="{{ old('rooms_total') }}" placeholder="e.g., 3" required>
                            @error('rooms_total')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="">-- Select Status --</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Describe the villa, amenities, and special features...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Images Section -->
                <div class="form-section">
                    <h3>Villa Images</h3>
                    
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail Image (Main Image)</label>
                        <div class="upload-area" id="thumbnailArea" onclick="document.getElementById('thumbnail').click()">
                            <p style="margin: 0; font-size: 16px;">📷 Click to upload thumbnail or drag & drop</p>
                            <p style="margin: 8px 0 0 0; color: #666; font-size: 12px;">JPG, PNG, GIF (Max 5MB)</p>
                        </div>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" style="display: none;">
                        <div class="image-preview" id="thumbnailPreview"></div>
                        @error('thumbnail')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="images">Gallery Images</label>
                        <div class="upload-area" id="imagesArea" onclick="document.getElementById('images').click()">
                            <p style="margin: 0; font-size: 16px;">🖼️ Click to upload gallery images or drag & drop</p>
                            <p style="margin: 8px 0 0 0; color: #666; font-size: 12px;">You can select multiple images</p>
                        </div>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;">
                        <div class="image-preview" id="imagesPreview"></div>
                        @error('images')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="form-buttons">
                    <a href="{{ route('admin.villas.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Villa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Thumbnail preview
const thumbnailInput = document.getElementById('thumbnail');
const thumbnailArea = document.getElementById('thumbnailArea');
const thumbnailPreview = document.getElementById('thumbnailPreview');

thumbnailInput.addEventListener('change', function() {
    previewThumbnail(this);
});

thumbnailArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    thumbnailArea.classList.add('dragover');
});

thumbnailArea.addEventListener('dragleave', () => {
    thumbnailArea.classList.remove('dragover');
});

thumbnailArea.addEventListener('drop', (e) => {
    e.preventDefault();
    thumbnailArea.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) {
        thumbnailInput.files = e.dataTransfer.files;
        previewThumbnail(thumbnailInput);
    }
});

function previewThumbnail(input) {
    thumbnailPreview.innerHTML = '';
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const item = document.createElement('div');
            item.className = 'image-item';
            item.innerHTML = `
                <img src="${e.target.result}" alt="Thumbnail">
                <button type="button" class="image-remove" onclick="removeThumbnail(event)">×</button>
            `;
            thumbnailPreview.appendChild(item);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeThumbnail(event) {
    event.preventDefault();
    thumbnailInput.value = '';
    thumbnailPreview.innerHTML = '';
}

// Gallery images preview
const imagesInput = document.getElementById('images');
const imagesArea = document.getElementById('imagesArea');
const imagesPreview = document.getElementById('imagesPreview');

imagesInput.addEventListener('change', function() {
    previewGalleryImages(this);
});

imagesArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    imagesArea.classList.add('dragover');
});

imagesArea.addEventListener('dragleave', () => {
    imagesArea.classList.remove('dragover');
});

imagesArea.addEventListener('drop', (e) => {
    e.preventDefault();
    imagesArea.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) {
        imagesInput.files = e.dataTransfer.files;
        previewGalleryImages(imagesInput);
    }
});

function previewGalleryImages(input) {
    imagesPreview.innerHTML = '';
    if (input.files) {
        for (let file of input.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const item = document.createElement('div');
                    item.className = 'image-item';
                    item.innerHTML = `
                        <img src="${e.target.result}" alt="Gallery image">
                    `;
                    imagesPreview.appendChild(item);
                };
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>

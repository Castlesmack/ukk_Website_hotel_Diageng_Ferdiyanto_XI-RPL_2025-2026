@extends('layouts.app')

@section('title', 'Edit Villa')

@section('content')
    <div style="display: grid; grid-template-columns: 200px 1fr; gap: 20px; margin: 0 -20px; padding: 0;">
        <!-- Sidebar -->
        <div style="background: #f8f9fa; padding: 20px; min-height: 100vh;">
            <h2 style="margin: 0 0 30px 0; font-size: 18px; font-weight: 600;">Menu</h2>
            <nav style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.dashboard') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📊
                    Dashboard</a>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <button
                        onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; text-align: left; font-weight: 500;">🏡
                        Manage</button>
                    <div id="manage-menu" style="display: none; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}"
                            style="padding: 8px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Homepage</a>
                    </div>
                </div>
                <a href="{{ route('admin.reservations.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">📅
                    Reservation</a>
                <a href="{{ route('admin.users.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">👥
                    Users</a>
                <a href="{{ route('admin.finances.index') }}"
                    style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px;">💰
                    Finance</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div style="padding: 20px; background: white; max-width: 800px;">
            <h1 style="margin: 0 0 20px 0; font-size: 28px;">Edit Villa</h1>

            <form action="{{ route('admin.villas.update', $villa) }}" method="POST" enctype="multipart/form-data"
                style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Villa *</label>
                    <input type="text" name="name" value="{{ old('name', $villa->name) }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                    @error('name') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug', $villa->slug) }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                    @error('slug') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kapasitas *</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $villa->capacity) }}"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                        @error('capacity') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jumlah Kamar *</label>
                        <input type="number" name="rooms_total" value="{{ old('rooms_total', $villa->rooms_total) }}"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                        @error('rooms_total') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Per Malam (Rp) *</label>
                    <input type="number" name="base_price" value="{{ old('base_price', $villa->base_price) }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" required>
                    @error('base_price') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Deskripsi *</label>
                    <textarea name="description"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;"
                        required>{{ old('description', $villa->description) }}</textarea>
                    @error('description') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status *</label>
                    <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                        required>
                        <option value="available" {{ old('status', $villa->status) === 'available' ? 'selected' : '' }}>
                            Available</option>
                        <option value="unavailable" {{ old('status', $villa->status) === 'unavailable' ? 'selected' : '' }}>
                            Unavailable</option>
                    </select>
                    @error('status') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Thumbnail Image</label>
                    @if($villa->thumbnail_path && file_exists(public_path($villa->thumbnail_path)))
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset($villa->thumbnail_path) }}" alt="Thumbnail"
                                style="max-width: 150px; border-radius: 4px;">
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">Current thumbnail</p>
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #666;">Leave empty to keep current. Max 2MB. Format: JPG, PNG, GIF</small>
                    @error('thumbnail') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Gallery Images</label>
                    @if($villa->images && count($villa->images) > 0)
                        <div style="margin-bottom: 15px;">
                            <p style="font-size: 12px; font-weight: 600; margin-bottom: 8px;">Current Gallery Images:</p>
                            <div
                                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px;">
                                @foreach($villa->images as $image)
                                    <div style="position: relative; border-radius: 4px; overflow: hidden;">
                                        <img src="{{ asset($image) }}" alt="Gallery"
                                            style="width: 100%; height: 100px; object-fit: cover;">
                                        <button type="button" onclick="deleteImage(this, '{{ $image }}');"
                                            style="position: absolute; top: 2px; right: 2px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 16px; line-height: 1;">×</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <input type="file" name="images[]" multiple accept="image/*"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <small style="color: #666;">You can select multiple images. Leave empty to keep current. Max 2MB per
                        image.</small>
                    @error('images') <small style="color: #dc3545;">{{ $message }}</small> @enderror
                </div>

                <script>
                    function deleteImage(button, imagePath) {
                        if (confirm('Are you sure you want to delete this image?')) {
                            const form = button.closest('form');
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'delete_images[]';
                            input.value = imagePath;
                            form.appendChild(input);
                            button.parentElement.style.opacity = '0.5';
                            button.disabled = true;
                        }
                    }
                </script>

                <div style="display: flex; gap: 10px;">
                    <button type="submit"
                        style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">Simpan</button>
                    <a href="{{ route('admin.villas.index') }}"
                        style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: 600;">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
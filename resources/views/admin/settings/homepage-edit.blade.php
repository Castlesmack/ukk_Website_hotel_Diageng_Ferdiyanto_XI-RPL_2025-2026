@extends('layouts.app')

@section('title', 'Edit Homepage')

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
                    <button onclick="document.getElementById('manage-menu').style.display = document.getElementById('manage-menu').style.display === 'none' ? 'flex' : 'none'"
                        style="padding: 12px; background: white; color: #333; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; text-align: left; font-weight: 500;">🏡
                        Manage</button>
                    <div id="manage-menu" style="display: none; flex-direction: column; gap: 5px; margin-left: 10px;">
                        <a href="{{ route('admin.villas.index') }}"
                            style="padding: 8px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; font-size: 13px;">
                            Villa</a>
                        <a href="{{ route('admin.settings.homepage') }}"
                            style="padding: 8px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 13px;">
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
        <div style="padding: 20px;">
            <h1 style="margin: 0 0 30px 0; font-size: 28px;">Edit Homepage</h1>

            @if ($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <strong>Error:</strong>
                    <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.homepage.update') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 30px;">
                @csrf

                <!-- Description Section -->
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                    <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Edit Description Beranda</h3>
                    <textarea name="description" rows="5" 
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: Arial; font-size: 14px; box-sizing: border-box;"
                        placeholder="Edit deskripsi homepage...">{{ $homepage->description ?? '' }}</textarea>
                </div>

                <!-- Image Slider Section -->
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                    <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Image Slider (Maksimal 5 Gambar)</h3>
                    
                    @if ($homepage->slider_images && count($homepage->slider_images) > 0)
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; margin-bottom: 20px;">
                            @foreach ($homepage->slider_images as $index => $image)
                                <div style="position: relative; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; background: #f5f5f5;">
                                    <img src="{{ asset($image) }}" alt="Slider Image" style="width: 100%; height: 100px; object-fit: cover;">
                                    <button type="submit" name="delete_image" value="{{ $index }}" 
                                        style="position: absolute; top: 5px; right: 5px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 12px; display: flex; align-items: center; justify-content: center;">
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: #666; margin-bottom: 20px;">Tidak ada gambar slider. Tambahkan gambar di bawah.</p>
                    @endif

                    <div style="display: grid; gap: 10px;">
                        <label style="display: block; font-weight: 500;">Tambah Gambar (Tersisa: {{ 5 - (count($homepage->slider_images ?? []) ?? 0) }}/5)</label>
                        <input type="file" name="slider_images[]" accept="image/*" multiple 
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;">
                        <small style="color: #666;">Pilih maksimal {{ 5 - (count($homepage->slider_images ?? []) ?? 0) }} gambar (JPEG, PNG, GIF, max 2MB per file)</small>
                    </div>
                </div>

                <!-- Villa Visibility & Order Section -->
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                    <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Urutan & Visibilitas Villa</h3>
                    <p style="color: #666; margin: 0 0 15px 0; font-size: 13px;">Centang villa yang ingin ditampilkan di halaman beranda. Urutan akan sesuai dengan urutan di bawah ini.</p>
                    
                    <div style="border: 1px solid #ddd; border-radius: 4px; max-height: 400px; overflow-y: auto;">
                        @foreach ($villas as $villa)
                            @php
                                $visibility = $villaVisibility->get($villa->id);
                                $isVisible = $visibility ? $visibility->is_visible : true;
                            @endphp
                            <div style="padding: 10px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                                <input type="checkbox" name="villa_visible[]" value="{{ $villa->id }}" 
                                    {{ $isVisible ? 'checked' : '' }}
                                    style="width: 18px; height: 18px; cursor: pointer;">
                                <label style="flex: 1; cursor: pointer; margin: 0;">
                                    <strong>{{ $villa->name }}</strong><br>
                                    <small style="color: #666;">Kapasitas: {{ $villa->capacity }} tamu | Harga: Rp {{ number_format($villa->base_price, 0, ',', '.') }}/malam</small>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Facilities Section -->
                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
                    <h3 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 600;">Edit Fasilitas</h3>
                    
                    @if ($facilities->count() > 0)
                        @foreach ($facilities as $category => $categoryFacilities)
                            <div style="margin-bottom: 20px;">
                                <h4 style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $category) }}
                                </h4>
                                <div style="border: 1px solid #ddd; border-radius: 4px; padding: 10px;">
                                    @foreach ($categoryFacilities as $facility)
                                        <div style="padding: 8px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
                                            <input type="checkbox" name="facilities[{{ $facility->id }}][visible]" 
                                                {{ $facility->is_visible ? 'checked' : '' }}
                                                style="width: 18px; height: 18px; cursor: pointer;">
                                            <label style="flex: 1; cursor: pointer; margin: 0;">{{ $facility->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="color: #666;">Belum ada fasilitas. <a href="{{ route('admin.settings.facilities') }}" style="color: #007bff;">Tambahkan fasilitas</a></p>
                    @endif
                </div>

                <!-- Submit Button -->
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
                        💾 Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

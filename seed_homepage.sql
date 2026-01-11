INSERT INTO homepage_settings (
        description,
        slider_images,
        created_at,
        updated_at
    )
VALUES (
        'Selamat datang di UKK Villa Kota Bunga Puncak. Kami menyediakan villa yang nyaman dan berkualitas tinggi dengan berbagai pilihan lokasi strategis. Nikmati pengalaman menginap yang tak terlupakan bersama keluarga dan teman-teman Anda. Setiap villa kami dilengkapi dengan fasilitas lengkap untuk kenyamanan Anda.',
        '[]',
        datetime('now'),
        datetime('now')
    );
INSERT INTO homepage_facilities (
        category,
        name,
        is_visible,
        "order",
        created_at,
        updated_at
    )
VALUES (
        'Public Facilities',
        'Parking area',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'Public Facilities',
        'WiFi in public areas',
        1,
        2,
        datetime('now'),
        datetime('now')
    ),
    (
        'Connectivity',
        'In-room internet',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'Transportation',
        'Bicycle rental',
        1,
        1,
        datetime('now'),
        datetime('now')
    ),
    (
        'Other Activities',
        'Garden',
        1,
        1,
        datetime('now'),
        datetime('now')
    );
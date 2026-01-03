-- SQLite-compatible schema for UKK_Villa booking system
-- Created: 2026-01-03 (converted)
PRAGMA foreign_keys = OFF;
DROP TABLE IF EXISTS invoices;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS room_type_availabilities;
DROP TABLE IF EXISTS villa_images;
DROP TABLE IF EXISTS villa_room_types;
DROP TABLE IF EXISTS villas;
DROP TABLE IF EXISTS feedbacks;
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    phone TEXT,
    role TEXT NOT NULL DEFAULT 'guest',
    -- values: guest,receptionist,admin
    email_verified_at DATETIME NULL,
    remember_token TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE villas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    capacity INTEGER NOT NULL DEFAULT 1,
    base_price NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    rooms_total INTEGER NOT NULL DEFAULT 1,
    description TEXT NULL,
    status TEXT NOT NULL DEFAULT 'active',
    -- active,inactive,maintenance
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE villa_room_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    villa_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    capacity INTEGER NOT NULL DEFAULT 1,
    price NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    rooms_count INTEGER NOT NULL DEFAULT 1,
    amenities TEXT NULL,
    description TEXT NULL,
    status TEXT NOT NULL DEFAULT 'available',
    -- available,unavailable
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (villa_id) REFERENCES villas(id) ON DELETE CASCADE
);
CREATE TABLE villa_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    villa_id INTEGER NOT NULL,
    url TEXT NOT NULL,
    alt TEXT NULL,
    is_primary INTEGER NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (villa_id) REFERENCES villas(id) ON DELETE CASCADE
);
CREATE TABLE room_type_availabilities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    villa_room_type_id INTEGER NOT NULL,
    date DATE NOT NULL,
    available_count INTEGER NOT NULL DEFAULT 0,
    price_override NUMERIC(12, 2) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (villa_room_type_id, date),
    FOREIGN KEY (villa_room_type_id) REFERENCES villa_room_types(id) ON DELETE CASCADE
);
CREATE TABLE bookings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    villa_id INTEGER NOT NULL,
    villa_room_type_id INTEGER NOT NULL,
    booking_code TEXT NOT NULL UNIQUE,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    nights INTEGER NOT NULL,
    rooms_booked INTEGER NOT NULL DEFAULT 1,
    guests INTEGER NOT NULL DEFAULT 1,
    total_price NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    status TEXT NOT NULL DEFAULT 'pending',
    -- pending,confirmed,cancelled,checked_in,completed
    is_verified INTEGER NOT NULL DEFAULT 0,
    verified_by INTEGER NULL,
    verified_at DATETIME NULL,
    payment_status TEXT NOT NULL DEFAULT 'unpaid',
    -- unpaid,pending,paid,failed,refunded
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (villa_id) REFERENCES villas(id) ON DELETE CASCADE,
    FOREIGN KEY (villa_room_type_id) REFERENCES villa_room_types(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE
    SET NULL
);
CREATE TABLE payments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    booking_id INTEGER NOT NULL,
    amount NUMERIC(12, 2) NOT NULL,
    payment_method TEXT NOT NULL DEFAULT 'bank_transfer',
    transaction_id TEXT NULL,
    proof_url TEXT NULL,
    paid_at DATETIME NULL,
    status TEXT NOT NULL DEFAULT 'pending',
    -- pending,completed,failed,refunded
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);
CREATE TABLE invoices (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    booking_id INTEGER NOT NULL,
    invoice_code TEXT NOT NULL UNIQUE,
    amount NUMERIC(12, 2) NOT NULL,
    pdf_url TEXT NULL,
    sent_via TEXT NOT NULL DEFAULT 'none',
    -- email,none
    sent_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);
CREATE TABLE feedbacks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    booking_id INTEGER NULL,
    responder_id INTEGER NULL,
    channel TEXT NOT NULL DEFAULT 'web',
    -- livechat,email,web
    message TEXT NOT NULL,
    response TEXT NULL,
    status TEXT NOT NULL DEFAULT 'open',
    -- open,answered,closed
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (responder_id) REFERENCES users(id) ON DELETE
    SET NULL,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE
    SET NULL
);
-- Optional view example (SQLite supports views)
CREATE VIEW vw_income_per_villa AS
SELECT v.id AS villa_id,
    v.name AS villa_name,
    SUM(p.amount) AS income
FROM villas v
    JOIN villa_room_types vrt ON vrt.villa_id = v.id
    JOIN bookings b ON b.villa_room_type_id = vrt.id
    JOIN payments p ON p.booking_id = b.id
    AND p.status = 'completed'
GROUP BY v.id,
    v.name;
PRAGMA foreign_keys = ON;
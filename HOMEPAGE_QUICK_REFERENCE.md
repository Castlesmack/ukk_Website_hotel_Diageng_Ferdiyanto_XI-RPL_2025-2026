# 🏠 Homepage Management - Quick Reference Guide

## Admin Panel Updates

### Navigation Change
In all admin pages, the sidebar now shows:
```
🏡 Manage (expandable menu)
   ├─ Villa
   └─ Homepage ← NEW
```

---

## 📝 Admin Pages Workflow

### 1️⃣ Edit Homepage Settings
**URL:** `http://localhost:8000/admin/settings/homepage`

**What You Can Do:**

#### Description Section
- Edit the main description text displayed above the villa grid
- Supports multi-line text with line breaks
- Click **"💾 Simpan Perubahan"** to save

#### Image Slider (Max 5 Images)
- Click **"Choose File"** to select images
- You can select multiple files at once
- Max 5 images total, 2MB per image
- Supported: JPEG, PNG, GIF
- Click **×** on existing image thumbnails to delete
- Images auto-rotate every 5 seconds on guest homepage

#### Villa Visibility & Order
- **Check** the checkbox to show villa on homepage
- **Uncheck** to hide villa
- **Order matters:** Villas appear in the order they're checked
- Only 1 villa at a time, but order is preserved

Example:
```
☑ Villa A  → Shows as 1st villa
☑ Villa C  → Shows as 2nd villa
☐ Villa B  → Hidden
☑ Villa D  → Shows as 3rd villa
```

#### Facilities
- See all facilities grouped by category
- Check ☑ to show facility on homepage
- Uncheck ☐ to hide facility
- Categories: Public Facilities, Connectivity, Other Activities, Transportation

---

### 2️⃣ Manage Facilities
**URL:** `http://localhost:8000/admin/settings/facilities`

**What You Can Do:**

#### Add New Facility
- Select a **Category:**
  - Public Facilities (🏛️)
  - Connectivity (📡)
  - Other Activities (🎯)
  - Transportation (🚗)
- Enter **Facility Name** (e.g., "Swimming Pool", "24/7 Security")
- Click **"➕ Tambah Fasilitas"**

#### Manage Existing Facilities
- Each facility shows its **status** (Terlihat/Tersembunyi)
- Click **"🗑️ Hapus"** to delete a facility
- To toggle visibility, edit from homepage-edit page

---

## 👥 Guest Homepage Views

### Before Login
**URL:** `http://localhost:8000/` or `http://localhost:8000/home`

Navigation shows:
```
🏠 UKK Villa | Home | Villa | Facility | [Login] [Register]
```

### After Login
Same URL, but navigation shows:
```
🏠 UKK Villa | Home | Villa | Facility | 👤 {Your Name} [Logout]
```

---

## 🎨 What Guests See

### 1. Navigation Bar (Sticky)
- Logo and menu
- Auth buttons (Login/Register) or username + Logout

### 2. Image Carousel
- Full-width banner with up to 5 images
- Auto-rotates every 5 seconds
- Manual control via ● dots below
- Click dots to jump to specific image

### 3. Description Section
- Centered text box
- Contains description you edited in admin panel
- Supports multiple paragraphs

### 4. Villa Grid
- Search bar (placeholder for future feature)
- Sort dropdown (placeholder for future feature)
- Grid of villa cards showing:
  - Villa image placeholder (🏠)
  - Villa name
  - Capacity: X tamu | Y kamar
  - Price: Rp X,XXX,XXX/malam
  - [Lihat Detail] button

### 5. Facilities Grid
- Icons + facility names grouped by category
- Only shows facilities you checked as visible
- 4-column responsive layout

### 6. Footer
- Copyright notice

---

## 🔒 Security Notes

- All admin settings pages require **admin login**
- All routes protected by `auth` + `admin` middleware
- Image uploads checked:
  - File type (must be image)
  - File size (max 2MB)
  - Count (max 5 total)
- CSRF token required on all forms
- Delete confirmations prevent accidents

---

## 📂 Where Things Are Stored

### Database
- **Homepage content:** `homepage_settings` table
  - Column: `slider_images` (JSON array of file paths)
  - Column: `description` (long text)

- **Villa visibility:** `villa_visibility` table
  - Per villa: is_visible (true/false), order (1, 2, 3...)

- **Facilities:** `homepage_facilities` table
  - Per facility: is_visible, category, order

### Files
- **Uploaded images:** `storage/uploads/homepage/`
  - Access via: `http://localhost:8000/storage/uploads/homepage/filename.jpg`

---

## 🧪 Quick Testing Steps

1. **Go to admin settings:**
   ```
   http://localhost:8000/admin/settings/homepage
   ```

2. **Try uploading images:**
   - Select 2-3 images
   - See them appear as thumbnails
   - Click a thumbnail's × to delete
   - Max should be 5 total

3. **Edit description:**
   - Type some text
   - Include line breaks
   - Save

4. **Order villas:**
   - Check/uncheck villas in desired order
   - Save

5. **View guest homepage:**
   ```
   http://localhost:8000/
   ```
   - See carousel with images
   - See description
   - See villa grid (only visible ones, in order you set)
   - See facilities (only visible ones)

6. **Add facility:**
   ```
   http://localhost:8000/admin/settings/facilities
   ```
   - Fill category and name
   - Click add
   - Go back to homepage-edit to make it visible

---

## 🐛 Troubleshooting

### Images don't appear in carousel
- Check that `storage` directory is writable
- Verify images in `storage/uploads/homepage/`
- Check browser console (F12) for JavaScript errors

### Homepage settings not saving
- Check that database tables were migrated
- Verify admin user has correct role

### Villas not reordering
- Remember: **Order is based on checkbox order**
- Unchecked villas are automatically hidden
- Save after making changes

### Facilities not showing
- Make sure facilities are checked as visible
- Verify facilities have `is_visible = true` in database

---

## 💾 Database Tables

```sql
-- Homepage settings
SELECT * FROM homepage_settings;

-- Villa visibility
SELECT * FROM villa_visibility;

-- Facilities
SELECT * FROM homepage_facilities;
```

---

## 🎯 Common Tasks

### Change Description
1. Go to `/admin/settings/homepage`
2. Edit description textarea
3. Click Save

### Add Image to Carousel
1. Go to `/admin/settings/homepage`
2. Section: "Image Slider (Maksimal 5 Gambar)"
3. Click "Choose File"
4. Select image
5. Click Save

### Remove Image from Carousel
1. Go to `/admin/settings/homepage`
2. Find image thumbnail
3. Click × button
4. Click Save

### Show/Hide Villa
1. Go to `/admin/settings/homepage`
2. Section: "Urutan & Visibilitas Villa"
3. Check ☑ to show / Uncheck ☐ to hide
4. Click Save

### Add Facility Type
1. Go to `/admin/settings/facilities`
2. Section: "Tambah Fasilitas Baru"
3. Select category
4. Enter name
5. Click "➕ Tambah Fasilitas"

### Hide Facility
1. Go to `/admin/settings/homepage`
2. Section: "Edit Fasilitas"
3. Uncheck the facility checkbox
4. Click Save

---

## 📞 Support

For issues or questions:
- Check database migrations ran: `php artisan migrate:status`
- Verify routes cached: `php artisan route:list | grep settings`
- Check storage permissions: `ls -la storage/uploads/`


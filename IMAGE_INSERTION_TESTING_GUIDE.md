# VILLA IMAGE INSERTION - TESTING GUIDE

## Quick Test Steps

### ✅ Test 1: Create New Villa with Images (Main Test)
```
1. Go to: /admin/villas/create
2. Fill Form:
   - Name: "Test Villa Images"
   - Capacity: 4
   - Rooms: 2
   - Price: 500000
   - Description: "Test villa for images"
   - Status: "Active" (MUST be "Active", not "Available")
3. Upload Images:
   - Thumbnail: 1 image
   - Gallery: 3+ images
4. Click: "Simpan"
```

**Expected Result**:
- ✅ Success message: "Villa created successfully!"
- ✅ Redirect to villa list
- ✅ New villa appears in list
- ✅ Images stored in `public/uploads/villas/`
- ✅ Can click villa name and see all images

---

### ✅ Test 2: Edit Villa and Add More Images
```
1. Go to: /admin/villas (list)
2. Click: Edit button on "Test Villa Images"
3. Check:
   - Status shows "Active" (not "Available")
   - Slug shown as read-only: "test-villa-images"
   - Current images displayed with × delete buttons
4. Upload More:
   - Click "Select files"
   - Add 2 more images
5. Delete One:
   - Click × on one of the existing images
6. Click: "Simpan"
```

**Expected Result**:
- ✅ Success message: "Villa updated successfully!"
- ✅ New images added
- ✅ Deleted image removed
- ✅ Click villa detail to verify

---

### ✅ Test 3: View Villa on Guest Homepage
```
1. Go to: /
2. Scroll: To "Villa" section
3. Find: "Test Villa Images"
4. Check:
   - Thumbnail image displays
   - Click on villa card
```

**Expected Result**:
- ✅ Thumbnail shows correctly
- ✅ Page loads villa detail

---

### ✅ Test 4: View Villa Gallery
```
1. From test 3, click villa card
2. Page: /villa/test-villa-images (or similar)
3. Check:
   - Main image displays
   - Gallery thumbnails below
   - Click thumbnails to change main image
4. Check:
   - Booking form works
   - Images stay when checking availability
```

**Expected Result**:
- ✅ All gallery images display
- ✅ Thumbnail switching works
- ✅ No console errors

---

### ⚠️ Test 5: Error Handling - Invalid File
```
1. Go to: /admin/villas/create
2. Fill basic fields
3. Try to upload:
   - A .txt file as thumbnail
   - A .pdf file as gallery image
4. Click: "Simpan"
```

**Expected Result**:
- ❌ Form rejects with validation error
- ❌ Message: "Only JPEG, PNG, GIF files allowed" (or similar)
- ❌ Form data preserved (old() values shown)
- ❌ No files uploaded

---

### ⚠️ Test 6: Error Handling - Oversized File
```
1. Go to: /admin/villas/create
2. Try to upload:
   - Image larger than 2MB
3. Click: "Simpan"
```

**Expected Result**:
- ❌ Form rejects with validation error
- ❌ Message: "File size exceeds 2MB" (or similar)

---

### ⚠️ Test 7: Error Handling - Permission Issue
```
1. Terminal: cd public/uploads/
2. Terminal: chmod 000 villas
3. Go to: /admin/villas/create
4. Fill form and add images
5. Click: "Simpan"
6. Terminal: chmod 755 villas (to restore)
```

**Expected Result**:
- ❌ Error message about directory not writable
- ❌ Villa NOT created
- ❌ Images NOT uploaded
- ✅ Clear error message displayed

---

## Expected File Structure

After tests, `public/uploads/villas/` should contain:
```
public/uploads/villas/
├── thumb_1704812400_abc12345.jpg      (Test villa thumbnail)
├── img_1704812400_def45678.jpg        (Gallery image 1)
├── img_1704812401_ghi78901.jpg        (Gallery image 2)
├── img_1704812402_jkl01234.jpg        (Gallery image 3)
└── ... (any additional uploads)
```

File names pattern:
- Thumbnails: `thumb_{time}_{random}.{ext}`
- Gallery: `img_{time}_{uniqid}.{ext}`

---

## Database Check

### Check database with SQLite:
```bash
sqlite3 database.sqlite
SELECT id, name, status, thumbnail_path, images FROM villas;
```

Expected output:
```
1|Test Villa Images|active|uploads/villas/thumb_1704812400_abc12345.jpg|["uploads/villas/img_1704812400_def45678.jpg","uploads/villas/img_1704812401_ghi78901.jpg","uploads/villas/img_1704812402_jkl01234.jpg"]
```

Key points:
- ✅ status = "active" (not "available")
- ✅ thumbnail_path = valid path
- ✅ images = valid JSON array
- ✅ All paths exist in public/uploads/villas/

---

## Browser Console

### No Errors Expected
Open DevTools (F12) → Console tab

Expected: No red errors (warnings OK)

If you see:
```
❌ 404 not found: uploads/villas/...
```

This means file path in database but file not on disk.

---

## Troubleshooting

### Images not appearing in villa detail?
1. Check browser console for 404 errors
2. Check database: `SELECT images FROM villas WHERE id=1;`
3. Check filesystem: `ls public/uploads/villas/`
4. Verify file permissions: `ls -la public/uploads/`

### Villa creation fails with validation error?
1. Check form status value is: active/inactive/maintenance
2. Check form has all required fields
3. Check server logs: `tail storage/logs/laravel.log`

### Images upload but don't show?
1. Check database images column is JSON array
2. Check Model cast: `'images' => 'array'`
3. Check view is using `asset()` helper
4. Clear browser cache: Ctrl+Shift+Del

### Slug field still shows in form?
1. Clear browser cache
2. Restart web server
3. Check file was edited: grep "slug" resources/views/admin/villas/create.blade.php

---

## Quick Fixes

### Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Fix permissions:
```bash
chmod -R 755 public/uploads/
chmod -R 755 storage/
```

### Reset database (WARNING: deletes data!):
```bash
php artisan migrate:fresh
php artisan db:seed
```

---

## Success Indicators

✅ All tests pass when:
1. Villa created with multiple images
2. Images display on homepage
3. Images display on villa detail
4. Can edit and add/remove images
5. Error messages appear for invalid files
6. Invalid files rejected with validation
7. File permissions issues show clear error
8. No console errors or 404s
9. Database shows valid JSON arrays
10. Files exist in public/uploads/villas/

---


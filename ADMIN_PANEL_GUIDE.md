# Admin Panel Guide - Villa Management System

## Overview
The admin panel provides comprehensive tools for managing villa properties, availability dates, and homepage content. This guide covers all features and workflows.

---

## 1. Villa Management

### 1.1 Accessing Villa Management
- **URL**: `/admin/villas`
- **Menu**: Click "Manage Villas" in the admin sidebar
- **Permission**: Admin users only

### 1.2 View All Villas
The villa index displays all properties in an organized grid layout with:
- **Thumbnail Image**: Property photo (shows 🏠 emoji if no image)
- **Villa Name**: Property title
- **Status Badge**: Active (green), Inactive (red), or Maintenance (yellow)
- **Price/Night**: Base rental price
- **Capacity**: Number of guests
- **Bedrooms**: Number of rooms
- **Unavailable Dates**: Count of closed dates if any
- **Action Buttons**: Edit and Delete

### 1.3 Create a New Villa

**Step 1: Navigate to Creation Form**
- Click the "+ Add Villa" button on the villa listing page
- Or access directly at `/admin/villas/create`

**Step 2: Fill Villa Information**
1. **Villa Name** (required)
   - Enter a unique property name
   - Example: "Villa Sunset Paradise", "Beachside Retreat"

2. **Base Price/Night** (required)
   - Enter nightly rental price in Rupiah
   - Use numbers only (no currency symbols)

3. **Guest Capacity** (required)
   - Total number of guests the villa can accommodate
   - Example: 6, 8, 10 guests

4. **Number of Bedrooms** (required)
   - Total bedroom count in the property

5. **Status** (required)
   - **Active**: Available for booking
   - **Inactive**: Temporarily unavailable
   - **Maintenance**: Under maintenance/repair

6. **Description** (optional)
   - Detailed property description
   - Include amenities, features, location details
   - Supports HTML formatting

**Step 3: Upload Images**

1. **Thumbnail Image** (optional)
   - Primary property photo
   - Displays in villa cards and listings
   - Recommended size: 800x600px minimum
   - Supported formats: JPG, PNG, GIF
   - Max file size: 5MB
   - Upload methods:
     - Click the upload area to select file
     - Drag and drop directly onto the area

2. **Gallery Images** (optional)
   - Multiple property photos
   - Can upload multiple images at once
   - Same size/format requirements as thumbnail
   - Images appear in property details page

**Step 4: Submit**
- Click "Create Villa" button
- System will display success message
- You'll be redirected to villa listing with new property

### 1.4 Edit an Existing Villa

**Access Edit Form**
- Click "Edit" button on any villa card
- Or access directly at `/admin/villas/{id}/edit`

**Update Villa Information**
- All fields from creation are editable
- Change any property details as needed

**Update Images**
1. **Replace Thumbnail**
   - Upload a new thumbnail
   - Old image is automatically deleted
   - Leave empty to keep current thumbnail

2. **Replace Gallery**
   - Upload new gallery images
   - All gallery images are replaced (not added to)
   - Leave empty to keep current gallery

3. **Delete Images**
   - Click the "×" button on existing images
   - Immediate removal from filesystem

**Manage Unavailable Dates**
1. **Add Closure Date**
   - Select a date using the date picker
   - Click "+ Add Date" button
   - Date appears as a tag in the list

2. **Remove Closure Date**
   - Click the "×" button on any date tag
   - Date is immediately removed

3. **Use Cases**
   - Maintenance periods
   - Cleaning schedules
   - Seasonal closures
   - Special events
   - Owner occupancy periods

**Save Changes**
- Click "Update Villa" button
- View success message
- Redirected to villa listing

### 1.5 Delete a Villa

**Delete Process**
1. Click "Delete" button on villa card
2. Confirm deletion in the browser confirmation dialog
3. System will:
   - Delete all images from server
   - Remove database record
   - Clean up associated data

**Important**: This action is **permanent** and cannot be undone.

---

## 2. Homepage Editor

### 2.1 Accessing Homepage Editor
- **URL**: `/admin/homepage/edit`
- **Menu**: Click "Edit Homepage" in the admin sidebar

### 2.2 Edit Homepage Description

1. **Text Area**
   - Click in the description field
   - Edit or replace homepage text
   - Supports HTML formatting
   - Include your property USP and key features

2. **Default Content**
   - System provides sample text
   - Replace with your own property description

### 2.3 Manage Image Slider

**Add Images**
1. Click or drag-and-drop into the upload area
2. Select multiple images to upload at once
3. Images display immediately in the preview grid

**Image Guidelines**
- Recommended size: 1920x1080px (16:9 aspect ratio)
- Supported formats: JPG, PNG, GIF
- Max file size: 5MB per image
- Use high-quality photos

**Remove Images**
- Click the "×" button on any image in the grid
- Image is immediately removed from slider

**Reorder Images**
- Not currently available via UI
- Images display in upload order
- Delete and re-upload to change order

### 2.4 Manage Facilities

**Add a Facility**
1. Click the "+ Add Facility" button
2. Enter facility name (e.g., "Swimming Pool", "WiFi")
3. Select or enter emoji icon
4. Click "Add" to confirm

**Facility Icons**
- Use any Unicode emoji
- Examples:
  - 🏊 Swimming
  - 📡 WiFi
  - 🍳 Kitchen
  - ❄️ Air Conditioning
  - 🅿️ Parking
  - 🎮 Entertainment
  - 🧘 Spa

**Edit a Facility**
- Click in the facility name/icon field
- Modify as needed
- Changes save automatically

**Delete a Facility**
- Click the "×" button next to facility
- Facility is removed from homepage

**Save Changes**
- Click "Save Changes" button
- Redirected to editor with success message

---

## 3. Data Validation & Error Handling

### 3.1 Validation Rules

**Villa Creation/Edit**
- Villa name: Required, max 191 characters
- Base price: Required, numeric, minimum 0
- Capacity: Required, integer, minimum 1
- Bedrooms: Required, integer, minimum 1
- Status: Required, must be active/inactive/maintenance
- Thumbnail: Optional, must be image, max 5MB
- Gallery images: Optional, each max 5MB
- Closed dates: Optional, must be valid JSON array

**Homepage Editor**
- Description: Optional, string
- Facility names: Required if facility exists
- Facility icons: Optional, must be valid emoji

### 3.2 Error Messages

When validation fails:
1. Error summary appears at top of form
2. Individual field errors display below each input
3. Problem items are highlighted
4. Form submission is blocked until fixed

**Common Errors**
- "This field is required" - Missing required input
- "This field must be numeric" - Price contains non-numeric characters
- "The uploaded file must be an image" - File type not supported
- "The uploaded file is too large" - File exceeds 5MB limit

### 3.3 Success Messages

After successful operations:
- Green success banner appears
- Describes what was completed
- Banner auto-hides after 5 seconds or click to dismiss

---

## 4. Image Management

### 4.1 Image Storage
- All images stored in `public/uploads/villas/`
- Automatic directory creation on first use
- Files named with timestamp and random string for uniqueness

### 4.2 Image Upload Process
1. Select image file
2. Browser preview shows before submission
3. Upload on form submit
4. Server validates file type and size
5. File stored in public directory
6. Path saved to database

### 4.3 Image Deletion
- Automatic deletion when:
  - Replacing with new image
  - Removing from gallery
  - Deleting villa
- Manual deletion not needed
- Database record updated immediately

### 4.4 Image Best Practices
- Use JPG format for photos (better compression)
- Optimize images before upload
- Maintain consistent aspect ratios
- Use descriptive filenames (for your reference)
- Test on mobile devices to ensure quality

---

## 5. Date Management (Unavailable Dates)

### 5.1 Purpose
Mark periods when villa is unavailable:
- Maintenance and cleaning
- Owner occupancy
- Special events
- Seasonal closures
- Management needs

### 5.2 How to Use
1. Go to villa edit page
2. Scroll to "Unavailable Dates" section
3. Select date from calendar picker
4. Click "+ Add Date"
5. Date appears as removable tag
6. Submit form to save

### 5.3 Viewing Unavailable Dates
- Index page shows count of unavailable dates
- Edit page lists all specific dates
- Hotel booking system will prevent bookings on these dates

---

## 6. Common Workflows

### Workflow 1: Add a New Villa
1. Navigate to `/admin/villas`
2. Click "+ Add Villa"
3. Fill all required fields
4. Upload thumbnail and gallery images
5. Click "Create Villa"

### Workflow 2: Update Villa Prices & Info
1. Go to Manage Villas
2. Find villa, click "Edit"
3. Update name, price, capacity, bedrooms
4. Click "Update Villa"

### Workflow 3: Mark Villa as Maintenance
1. Go to Manage Villas
2. Click "Edit" on villa
3. Change status to "Maintenance"
4. Add closure dates for maintenance period
5. Click "Update Villa"

### Workflow 4: Refresh Property Photos
1. Go to villa edit page
2. Upload new thumbnail/gallery images
3. Old images auto-deleted
4. Click "Update Villa"
5. New images now show on listing

### Workflow 5: Update Homepage
1. Navigate to "Edit Homepage"
2. Update description text
3. Upload new slider images
4. Manage facilities (add/edit/delete)
5. Click "Save Changes"

---

## 7. Troubleshooting

### Issue: Image upload fails
**Solutions:**
- Check file is actual image (JPG/PNG/GIF)
- Verify file size is under 5MB
- Try different image format
- Clear browser cache and try again

### Issue: Validation error on villa name
**Solutions:**
- Check name is not longer than 191 characters
- Ensure at least 1 character entered
- Try different name (avoid special characters)

### Issue: Unavailable dates not saving
**Solutions:**
- Ensure date is selected before clicking Add
- Check browser console for errors
- Try different date
- Refresh page and try again

### Issue: Images not displaying in cards
**Solutions:**
- Verify upload was successful (no error message)
- Check image file size and format
- Clear browser cache
- Try uploading image again

---

## 8. Best Practices

1. **Use Descriptive Names**
   - "Villa Sunset Beachfront" instead of "Villa 1"
   - Makes management easier

2. **Organize Closure Dates**
   - Plan maintenance during low-season
   - Add dates well in advance
   - Update as schedules change

3. **Maintain Image Quality**
   - High-quality photos improve bookings
   - Consistent style and lighting
   - Show key features and amenities

4. **Keep Descriptions Updated**
   - Highlight current amenities
   - Update for seasonal changes
   - Include recent renovations

5. **Regular Homepage Updates**
   - Refresh slider images seasonally
   - Update facilities list as changes occur
   - Keep description current and engaging

---

## 9. System Architecture

### Database Tables
- `villas` - Property information (name, price, capacity, etc.)
  - `thumbnail_path` - Path to primary image
  - `images` - JSON array of gallery image paths
  - `closed_dates` - JSON array of unavailable dates
  - `closed_dates_json` - Serialized date data

### File Structure
```
public/
├── uploads/
│   └── villas/
│       ├── 1704538800_thumbnail_abc12345.jpg
│       ├── 1704538801_gal123456.jpg
│       └── 1704538802_gal234567.jpg
```

### Routes
- `GET /admin/villas` - List all villas
- `GET /admin/villas/create` - Create form
- `POST /admin/villas` - Store new villa
- `GET /admin/villas/{id}/edit` - Edit form
- `PUT /admin/villas/{id}` - Update villa
- `DELETE /admin/villas/{id}` - Delete villa
- `GET /admin/homepage/edit` - Homepage editor
- `PUT /admin/homepage` - Update homepage

---

## 10. Support & Help

### Getting Help
- Check validation error messages (usually specific)
- Review this guide for detailed instructions
- Check browser console for JavaScript errors
- Ensure all required fields are completed

### Reporting Issues
When reporting problems, include:
- What you were trying to do
- Error message (if any)
- Browser type and version
- Screenshots if helpful

---

**Last Updated**: January 6, 2026
**Version**: 1.0
**Admin Panel**: Ready for Production

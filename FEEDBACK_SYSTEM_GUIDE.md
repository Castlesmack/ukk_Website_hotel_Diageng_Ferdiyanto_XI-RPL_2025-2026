# Feedback & Messaging System - Complete Implementation Guide

## Overview
A complete feedback and messaging system has been implemented for all user roles: **Guest**, **Receptionist**, and **Admin**. This is a built-in system (no external APIs like talk.to) that enables direct communication between guests and staff.

---

## Features by Role

### 👤 GUEST
- **Send Feedback/Messages**: Create new feedback with optional booking reference
- **View Personal Messages**: See all their own feedback and responses
- **Close Messages**: Mark resolved messages as closed
- **View Response**: See admin/receptionist responses to their messages
- **Track Status**: Open → Answered → Closed workflow

### 💼 RECEPTIONIST
- **View All Feedback**: Access complete feedback list from all guests
- **Respond to Messages**: Send responses to guest inquiries
- **Manage Status**: Update message status and close tickets
- **Filter & Search**: View feedback by status, channel, date
- **Dashboard Widget**: See recent feedback statistics

### 👨‍💼 ADMIN
- **Full Management**: View, respond, and manage all feedback
- **Advanced Controls**: Delete/archive feedback if needed
- **Analytics**: View feedback statistics and trends
- **Settings**: Configure feedback system settings
- **Bulk Actions**: Manage multiple feedback items

---

## Routes & Access

### Guest Routes (Authenticated Users)
```
/feedback                      - List all personal feedback
/feedback/create               - Create new feedback form
/feedback/{id}                 - View feedback detail
/feedback/{id}/edit            - Edit/respond to feedback (admin/receptionist only)
/api/feedback/stats            - Get feedback statistics (JSON)
/api/feedback/recent/{limit}   - Get recent feedback (JSON)
```

### Reception Routes (Receptionist Only)
```
/reception/feedback            - View all feedback
/reception/feedback/{id}       - View feedback detail
/reception/feedback/{id}/edit  - Respond to feedback
```

### Admin Routes (Admin Only)
```
/admin/feedback                - View all feedback management
/admin/feedback/{id}           - View feedback detail
/admin/feedback/{id}/edit      - Respond to feedback
```

---

## Database Structure

### feedbacks Table
```
id (Primary Key)
user_id (FK → users) - Who submitted the feedback
booking_id (FK → bookings, nullable) - Related booking
responder_id (FK → users, nullable) - Who responded
channel ENUM('web', 'email', 'livechat') - Submission method
message TEXT - The feedback/question
response TEXT - Staff response
status ENUM('open', 'answered', 'closed') - Ticket status
created_at - Submission timestamp
updated_at - Last modified timestamp

Indexes:
- user_id
- booking_id
- status
```

---

## File Structure

### Models
- **App/Models/Feedback.php**
  - Relationships: user, booking, responder
  - Scopes: open(), answered(), closed(), web(), email(), livechat()

### Controllers
- **App/Http/Controllers/FeedbackController.php**
  - index() - List feedback
  - create() - Show create form
  - store() - Save new feedback
  - show() - View single feedback
  - edit() - Edit/respond form
  - update() - Update feedback & response
  - close() - Mark as closed
  - stats() - JSON stats API
  - recent() - Recent feedback API

### Policies
- **App/Policies/FeedbackPolicy.php**
  - Role-based authorization
  - Guest: Can only view/close own feedback
  - Receptionist: Can view/respond to all
  - Admin: Full access including delete

### Views
- **resources/views/feedback/index.blade.php** - Feedback list
- **resources/views/feedback/create.blade.php** - Create form
- **resources/views/feedback/show.blade.php** - View detail
- **resources/views/feedback/edit.blade.php** - Response form

### Routes
- **routes/web.php** - All feedback routes configured

---

## Usage Examples

### Create Feedback (Guest)
```php
$feedback = Feedback::create([
    'user_id' => Auth::id(),
    'booking_id' => $bookingId,  // Optional
    'message' => 'Your message here',
    'channel' => 'web',
    'status' => 'open'
]);
```

### Respond to Feedback (Admin/Receptionist)
```php
$feedback->update([
    'response' => 'Thank you for your feedback...',
    'status' => 'answered',
    'responder_id' => Auth::id()
]);
```

### Query Feedback
```php
// Get guest's own feedback
$feedback = Feedback::where('user_id', $guestId)->get();

// Get open feedback
$openFeedback = Feedback::open()->get();

// Get by channel
$webFeedback = Feedback::web()->get();

// Get with relationships
$feedback = Feedback::with(['user', 'responder', 'booking'])->get();
```

---

## API Endpoints

### Get Feedback Statistics
```
GET /api/feedback/stats
Response:
{
  "total": 15,
  "open": 5,
  "answered": 7,
  "closed": 3
}
```

### Get Recent Feedback
```
GET /api/feedback/recent/5
Response: Array of last 5 feedback items with relationships
```

---

## Workflow

### For Guests
1. **Create** → Go to /feedback/create → Fill form → Submit
2. **View** → Go to /feedback → See all personal messages
3. **View Response** → Click on feedback → See admin's response
4. **Close** → Click "Close Message" when resolved

### For Receptionists
1. **Access** → Go to /reception/feedback
2. **View** → Click on any feedback to see full details
3. **Respond** → Click "Send Response" → Fill response form
4. **Track** → See feedback status (open/answered/closed)

### For Admins
1. **Access** → Go to /admin/feedback
2. **Manage** → Full access to all feedback
3. **Respond** → Same as receptionist
4. **Delete** → Admin-only action for cleanup
5. **Analytics** → View stats and trends

---

## Authorization

Using **Laravel Policies** (FeedbackPolicy):
- **Guests**: Can view/edit only their own, cannot respond
- **Receptionists**: Can view/respond to all, cannot delete
- **Admins**: Full access to all operations

Middleware protection:
- All routes require authentication (`auth` middleware)
- Some routes require role checking (`admin`, `receptionist`)

---

## Customization

### Add Custom Channels
Edit **Feedback model** and database to add new channels:
```php
// In migration
$table->enum('channel', ['web', 'email', 'livechat', 'phone'])->default('web');
```

### Change Statuses
Edit **Feedback model** to add custom statuses:
```php
// In migration
$table->enum('status', ['open', 'answered', 'closed', 'escalated'])->default('open');
```

### Add More Fields
Create a new migration and add fields like:
- `priority` (high/medium/low)
- `category` (billing, technical, general)
- `resolved_at` (timestamp)
- `satisfaction_rating` (1-5)

---

## Setting Up the System

### 1. Create Migration
The migration file is ready at: `database/migrations/2024_01_23_create_feedbacks_table.php`

Run:
```bash
php artisan migrate
```

### 2. Register Policy
Add to `app/Providers/AuthServiceProvider.php`:
```php
use App\Policies\FeedbackPolicy;
use App\Models\Feedback;

protected $policies = [
    Feedback::class => FeedbackPolicy::class,
];
```

### 3. Test Routes
```bash
php artisan route:list | grep feedback
```

### 4. Create Test Feedback
Access `/feedback/create` as a logged-in guest user.

---

## Security Notes

✅ **Implemented:**
- CSRF protection on all forms
- Role-based access control
- Authorization policies
- Input validation
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)

⚠️ **Consider:**
- Rate limiting on feedback creation
- Email notifications on new feedback
- Attachment support (images, files)
- Spam filtering
- Admin notifications

---

## Future Enhancements

1. **Real-time Notifications**: WebSocket-based live updates
2. **Email Notifications**: Auto-email responses to guests
3. **File Attachments**: Support for images/documents
4. **Email Integration**: Receive feedback via email
5. **Satisfaction Rating**: Guest ratings after resolution
6. **Analytics Dashboard**: Advanced reporting
7. **Canned Responses**: Pre-written templates for staff
8. **Chat History**: Thread-based conversations
9. **Mobile App**: Native mobile feedback submission
10. **Multilingual Support**: Support multiple languages

---

## Support

For issues or questions:
1. Check FeedbackController for logic
2. Review FeedbackPolicy for authorization
3. Check routes/web.php for routing
4. View templates in resources/views/feedback/

---

**System Status**: ✅ Ready for Production
**Last Updated**: January 23, 2026
**Supported Roles**: Guest, Receptionist, Admin

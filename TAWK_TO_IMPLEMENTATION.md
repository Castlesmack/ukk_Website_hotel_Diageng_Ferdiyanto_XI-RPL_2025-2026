# Tawk.to-Like Chat Implementation Guide

## Overview
This is a complete real-time chat system similar to Tawk.to, built directly into the UKK Villa platform. It includes:
- Live customer-admin conversations
- Real-time messaging with auto-refresh
- Admin dashboard for managing chats
- Read receipts and typing indicators
- Conversation history and notes
- Unread badge counter

## Features

### For Customers (Chat Widget)
✅ Floating chat button at bottom-right corner
✅ Real-time message sending/receiving
✅ Online status indicator
✅ Message history loading
✅ Read receipts (✓ or ✓✓)
✅ Auto-refresh every 3 seconds
✅ Typing indicator (dots animation)
✅ Unread badge on button
✅ Business hours display

### For Admin (Dashboard)
✅ Live conversation list
✅ Real-time message updates
✅ Status filtering (Active/Closed)
✅ Assign conversations to team members
✅ Internal notes on conversations
✅ Close conversations
✅ Unread count per conversation
✅ Quick reply interface

## Installation

### 1. Run Migrations
```bash
php artisan migrate
```

This creates two tables:
- `chat_conversations` - Stores chat conversations
- `chat_messages` - Stores individual messages

### 2. Update AuthServiceProvider
Add to `app/Providers/AuthServiceProvider.php`:
```php
use App\Models\ChatConversation;
use App\Policies\ChatConversationPolicy;

public function boot()
{
    $this->registerPolicies();
    Gate::policy(ChatConversation::class, ChatConversationPolicy::class);
}
```

### 3. Clear Cache
```bash
php artisan config:cache
php artisan cache:clear
```

## File Structure

### Models
- `app/Models/ChatConversation.php` - Conversation model with relationships
- `app/Models/ChatMessage.php` - Message model with query scopes

### Controllers
- `app/Http/Controllers/ChatController.php` - All chat API endpoints and logic

### Views
- `resources/views/components/chat-widget.blade.php` - Customer-facing chat widget
- `resources/views/admin/chat/index.blade.php` - Admin dashboard

### Policies
- `app/Policies/ChatConversationPolicy.php` - Authorization rules

### Migrations
- `database/migrations/2026_01_23_create_chat_conversations_table.php`
- `database/migrations/2026_01_23_create_chat_messages_table.php`

## Database Schema

### chat_conversations
```
- id (primary key)
- user_id (foreign key to users)
- visitor_name (string) - customer name
- visitor_email (string) - customer email
- visitor_phone (string) - customer phone
- subject (string) - conversation subject
- status (enum: active, closed, archived)
- assigned_to (foreign key to users) - admin assigned
- last_message_at (timestamp)
- last_message_by (enum: user, admin)
- read_by_user (boolean)
- read_by_admin (boolean)
- notes (text) - internal admin notes
- timestamps
```

### chat_messages
```
- id (primary key)
- conversation_id (foreign key)
- user_id (foreign key)
- sender_type (enum: user, admin)
- message (longtext)
- attachments (json) - for future file uploads
- is_typing (boolean) - for typing indicators
- read_at (timestamp)
- timestamps
```

## API Endpoints

### Customer Endpoints (Auth Required)

**GET** `/api/chat/conversation`
- Get or create conversation for current user

**GET** `/api/chat/{conversationId}/messages`
- Get all messages for a conversation

**POST** `/api/chat/send`
- Send a new message
- Body: `{ conversation_id, message }`

**POST** `/api/chat/read`
- Mark messages as read
- Body: `{ conversation_id }`

**GET** `/api/chat/unread-count`
- Get unread conversation count

### Admin Endpoints (Auth + Admin Required)

**GET** `/admin/chat`
- View all conversations with filters

**GET** `/admin/chat/{conversationId}`
- Get conversation details

**POST** `/admin/chat/{conversationId}/send`
- Send reply to customer

**POST** `/admin/chat/{conversationId}/assign`
- Assign conversation to admin
- Body: `{ assigned_to: userId }`

**POST** `/admin/chat/{conversationId}/close`
- Close conversation

**POST** `/admin/chat/{conversationId}/notes`
- Save internal notes
- Body: `{ notes: "..." }`

## Usage Guide

### For Customers

1. **Open Chat Widget**
   - Click the floating chat button (💬) at bottom-right
   - Widget opens with welcome message

2. **Send Message**
   - Type message in input field
   - Click "Kirim" or press Enter
   - Message appears immediately

3. **View Status**
   - Online indicator shows in header
   - Read receipts (✓ = sent, ✓✓ = read)
   - Unread badge shows on button

4. **View History**
   - Open widget to see conversation history
   - Auto-loads last 50 messages
   - Auto-refreshes every 3 seconds

### For Admin

1. **Access Dashboard**
   - Go to `/admin/chat`
   - See all active conversations

2. **Select Conversation**
   - Click conversation in left panel
   - View message history

3. **Send Reply**
   - Type response in input field
   - Click "Send"
   - Message appears to customer in real-time

4. **Add Notes**
   - Enter internal notes
   - Click "Save Notes"
   - Notes only visible to admin

5. **Manage Conversations**
   - Assign to other admins
   - Close when resolved
   - Filter by status

## Authorization

### Guest Users
- Cannot send messages
- See login/register prompt in chat widget
- Can view information only

### Authenticated Users
- Can create conversations
- Can send/receive messages
- View their own conversations only

### Admin/Receptionist
- Can view all conversations
- Can respond to any conversation
- Can assign conversations
- Can add internal notes
- Can close conversations

## Real-Time Features

### Auto-Refresh
- Messages auto-refresh every 3 seconds
- Unread badge updates automatically
- No manual refresh needed

### Read Receipts
- Single ✓ = message sent
- Double ✓✓ = message read by recipient

### Typing Indicator
- Animated dots show when admin is typing (future implementation)

### Online Status
- Green dot indicator in header
- Shows "Online" when team is available

## Customization

### Change Colors
Edit `resources/views/components/chat-widget.blade.php`:
```blade
class="bg-gradient-to-r from-orange-400 to-orange-500"
```
Change orange to your brand color (blue, red, green, etc.)

### Change Business Hours
Edit the widget:
```blade
📌 Tim kami siap melayani Anda setiap hari 09:00 - 18:00
```

### Change Support Name
Edit header:
```blade
<h3 class="font-bold text-lg">Ade Villa Support</h3>
```

### Adjust Refresh Rate
In JavaScript, change interval (milliseconds):
```javascript
autoRefreshInterval = setInterval(loadMessages, 3000); // 3 seconds
```

## Performance Optimization

### Database Indexes
- Created on: user_id, conversation_id, sender_type, status
- Improves query speed for filtering

### Pagination
- Admin dashboard loads 20 conversations per page
- Prevents loading all conversations at once

### Auto-Refresh Limits
- Refreshes only when widget is open
- Stops on page unload to save resources

## Troubleshooting

### Messages not sending
1. Check browser console for errors
2. Verify CSRF token in meta tag
3. Check user authentication status

### Widget not appearing
1. Verify chat-widget component is included in layout
2. Check browser console for JavaScript errors
3. Ensure migrations were run

### Admin dashboard not loading
1. Verify user is authenticated and is admin
2. Check routes registered in web.php
3. Clear cache: `php artisan cache:clear`

### Unread badges not updating
1. Check that auto-refresh interval is running
2. Verify API endpoint is responding
3. Check browser console for fetch errors

## Future Enhancements

- [ ] File upload support
- [ ] Rich text editor
- [ ] Canned responses (quick replies)
- [ ] Visitor tracking (IP, browser, pages visited)
- [ ] Email notifications
- [ ] Department routing
- [ ] Chat history export
- [ ] WebSocket real-time (instead of polling)
- [ ] Mobile app support
- [ ] Multi-language support
- [ ] Customer satisfaction rating
- [ ] Chatbot integration

## API Response Examples

### Send Message - Success
```json
{
  "success": true,
  "message": {
    "id": 1,
    "conversation_id": 1,
    "sender": "Anda",
    "sender_type": "user",
    "message": "Halo, saya ingin bertanya tentang...",
    "timestamp": "2026-01-23 10:30:45",
    "time_ago": "Baru saja",
    "is_read": false
  }
}
```

### Get Unread Count - Success
```json
{
  "success": true,
  "unread_count": 3
}
```

### Admin - Get Conversations
```json
{
  "success": true,
  "conversations": [
    {
      "id": 1,
      "visitor_name": "John Doe",
      "visitor_email": "john@example.com",
      "subject": "Booking Inquiry",
      "status": "active",
      "last_message_at": "2026-01-23 10:30:00",
      "last_message_by": "user",
      "unread": 2
    }
  ]
}
```

## Support
For issues or questions, contact the development team or check the console logs for detailed error messages.

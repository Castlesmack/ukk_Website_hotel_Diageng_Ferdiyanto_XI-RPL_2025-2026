# Quick Setup - Tawk.to-Like Chat

## Setup in 5 Minutes

### Step 1: Run Migrations
```bash
cd c:\Users\HP\UKK_Villa
php artisan migrate
```

This creates the `chat_conversations` and `chat_messages` tables.

### Step 2: Register Policy (Optional but Recommended)
Edit `app/Providers/AuthServiceProvider.php` and add in the `boot()` method:

```php
use App\Models\ChatConversation;
use App\Policies\ChatConversationPolicy;

Gate::policy(ChatConversation::class, ChatConversationPolicy::class);
```

### Step 3: Clear Cache
```bash
php artisan config:cache
php artisan cache:clear
```

### Step 4: Test the Chat Widget
1. Go to the website frontend (any page)
2. Look for the floating chat button (💬) at bottom-right
3. Click to open chat
4. If logged in, send a test message
5. Message should appear immediately

### Step 5: Test Admin Dashboard
1. Log in as admin
2. Go to `/admin/chat`
3. You should see the admin chat interface
4. Select a conversation to view messages

## Verification Checklist

- [ ] Database tables created (`chat_conversations`, `chat_messages`)
- [ ] Chat widget appears on frontend pages
- [ ] Can open/close chat widget
- [ ] Login page appears for non-authenticated users
- [ ] Can send messages when logged in
- [ ] Admin dashboard loads at `/admin/chat`
- [ ] Can see customer conversations in admin panel
- [ ] Can send replies from admin panel
- [ ] Messages appear in real-time (3-second auto-refresh)

## Files Added/Modified

### New Files Created
- `app/Models/ChatConversation.php`
- `app/Models/ChatMessage.php`
- `app/Http/Controllers/ChatController.php`
- `app/Policies/ChatConversationPolicy.php`
- `resources/views/components/chat-widget.blade.php` (replaced)
- `resources/views/admin/chat/index.blade.php`
- `database/migrations/2026_01_23_create_chat_conversations_table.php`
- `database/migrations/2026_01_23_create_chat_messages_table.php`

### Modified Files
- `routes/web.php` - Added chat routes
- `resources/views/layouts/app.blade.php` - Includes chat widget

## Feature Highlights

### Customer Features
- 💬 Floating chat widget
- ✅ Real-time messaging
- 🔄 Auto-refresh (3 seconds)
- ✓✓ Read receipts
- 🌐 Online status indicator
- 📱 Mobile responsive
- 🎨 Beautiful gradient UI

### Admin Features
- 📊 Live conversation dashboard
- 👤 Customer information display
- 🔀 Assign conversations
- 📝 Internal notes
- ⏹️ Close conversations
- 🔔 Unread indicators
- 📱 Responsive design

## Common Issues & Solutions

### Issue: Chat widget doesn't appear
**Solution:**
1. Clear browser cache (Ctrl+Shift+Delete)
2. Check console (F12) for errors
3. Verify `chat-widget` component is included in `app.blade.php`

### Issue: Messages not sending
**Solution:**
1. Verify you're logged in
2. Check console for error messages
3. Ensure CSRF token exists in meta tag

### Issue: Admin dashboard shows blank
**Solution:**
1. Ensure you're logged in as admin
2. Verify database tables were created (`php artisan migrate`)
3. Check user roles (`is_admin = 1`)

### Issue: Messages not updating in real-time
**Solution:**
1. Check network tab (F12) for failed API calls
2. Verify routes are registered correctly
3. Clear cache: `php artisan config:cache`

## API Endpoints Summary

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/chat/conversation` | Create/get conversation |
| GET | `/api/chat/{id}/messages` | Get messages |
| POST | `/api/chat/send` | Send message |
| GET | `/api/chat/unread-count` | Get unread count |
| GET | `/admin/chat` | View conversations |
| GET | `/admin/chat/{id}` | View single conversation |
| POST | `/admin/chat/{id}/send` | Reply to customer |
| POST | `/admin/chat/{id}/close` | Close conversation |

## Testing Commands

### Test Database
```bash
# Check if tables exist
php artisan tinker
>>> Schema::hasTable('chat_conversations')
>>> Schema::hasTable('chat_messages')
```

### Test Routes
```bash
php artisan route:list | grep chat
```

### Test API
```bash
# Open browser console and run:
fetch('/api/chat/conversation', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    }
}).then(r => r.json()).then(console.log)
```

## Next Steps

1. **Customize Colors** - Edit the gradient colors in chat-widget.blade.php
2. **Add Team Members** - Create admin/receptionist accounts
3. **Set Business Hours** - Update the business hours text
4. **Train Team** - Show admins how to use the dashboard
5. **Monitor Conversations** - Check admin dashboard regularly

## Support

For detailed documentation, see: `TAWK_TO_IMPLEMENTATION.md`

For issues, check:
1. Browser console (F12)
2. Laravel logs (`storage/logs/`)
3. Database records (`chat_conversations`, `chat_messages` tables)

---

**Ready to use!** Your Tawk.to-like chat system is now active on your website.

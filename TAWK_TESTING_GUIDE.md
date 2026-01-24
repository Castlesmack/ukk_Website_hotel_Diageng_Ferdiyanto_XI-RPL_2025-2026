# Tawk.to Chat System - Testing & Troubleshooting

## 🧪 Pre-Launch Checklist

### Installation Verification
- [ ] Migrations run successfully: `php artisan migrate`
- [ ] No migration errors in console
- [ ] Both tables created: `chat_conversations`, `chat_messages`
- [ ] Routes registered: `php artisan route:list | grep chat`
- [ ] Chat widget component exists: `resources/views/components/chat-widget.blade.php`
- [ ] Admin dashboard exists: `resources/views/admin/chat/index.blade.php`

### Frontend Testing
- [ ] Chat widget button appears at bottom-right corner (💬)
- [ ] Widget opens smoothly when clicked
- [ ] Widget closes when clicking X or button again
- [ ] "Login" prompt appears for non-authenticated users
- [ ] Chat widget is responsive on mobile
- [ ] Text input field is functional
- [ ] Send button sends messages

### Backend Testing
- [ ] Admin can access `/admin/chat` dashboard
- [ ] Conversations list loads
- [ ] Can click conversation to view messages
- [ ] Can send reply from admin panel
- [ ] Messages appear in real-time (3 sec refresh)
- [ ] Can add internal notes
- [ ] Can close conversations
- [ ] Badge count updates

### Database Testing
```bash
# Check tables exist
php artisan tinker
>>> Schema::hasTable('chat_conversations')  # Should return: true
>>> Schema::hasTable('chat_messages')        # Should return: true

# Check sample data
>>> \App\Models\ChatConversation::count()  # Returns: 0 (new)
>>> \App\Models\ChatMessage::count()       # Returns: 0 (new)
```

---

## 🧪 Manual Testing Steps

### Test 1: Guest User Experience
```
1. Open website in incognito window
2. Scroll to find 💬 button
3. Click chat button
4. Should see:
   - Header "Ade Villa Support"
   - Green "Online" indicator
   - Welcome message
   - "Login" and "Daftar" buttons
5. Click Login
6. Should redirect to login page
7. Result: ✅ PASS
```

### Test 2: Authenticated User Chat
```
1. Log in with a test account
2. Find 💬 button
3. Click to open chat
4. Type test message: "Hello, this is a test"
5. Click "Kirim" button
6. Message appears on right side with orange background
7. Wait 5 seconds
8. Should see:
   - Your message with timestamp
   - ✓ indicator (sent)
9. Result: ✅ PASS

Expected console output:
- No JavaScript errors
- Network request to /api/chat/send
- Response status: 200 OK
```

### Test 3: Admin Response
```
1. In another window, log in as admin
2. Go to /admin/chat
3. Should see conversation list
4. Click on the test conversation
5. See your test message on left side
6. Type admin reply: "Thanks for testing!"
7. Click Send
8. Go back to first window
9. Wait for auto-refresh (3 seconds)
10. Should see admin message appear
11. Your message should show ✓✓ (read)
12. Result: ✅ PASS
```

### Test 4: Message History
```
1. Chat window already has messages
2. Close chat widget (click X)
3. Open it again
4. Should still see previous messages
5. Messages should be in order
6. Oldest at top, newest at bottom
7. Result: ✅ PASS
```

### Test 5: Unread Badge
```
1. Customer sends message
2. Admin reads it
3. Close admin dashboard
4. Customer sends new message
5. Admin opens dashboard
6. Badge shows [1] on conversation
7. Result: ✅ PASS
```

### Test 6: Mobile Responsiveness
```
1. Open chat widget on desktop (works? ✓)
2. Resize window to 375px width (iPhone size)
3. Chat widget should adapt
4. No horizontal scroll
5. Text readable
6. Buttons clickable
7. Test on actual phone if possible
8. Result: ✅ PASS
```

---

## 🔍 Debugging Checklist

### Issue: Chat widget not appearing

**Check 1: Is component included?**
```bash
# Check if app.blade.php includes chat widget
grep -n "chat-widget" resources/views/layouts/app.blade.php
# Should output: @include('components.chat-widget')
```

**Check 2: Browser console**
```javascript
// Open browser (F12), go to Console tab
// Run:
document.getElementById('chat-widget')
// Should return: <div id="chat-widget">...</div>
// If null, component not loaded
```

**Check 3: CSS visibility**
```javascript
// In console:
const widget = document.getElementById('chat-widget')
window.getComputedStyle(widget).display
// Should return: block (not none)
```

**Fix: Clear cache and refresh**
```bash
php artisan config:cache
php artisan cache:clear
# Hard refresh browser: Ctrl+Shift+Delete then Ctrl+F5
```

---

### Issue: Messages not sending

**Check 1: Authentication**
```javascript
// In console, check if authenticated:
fetch('/api/chat/unread-count')
  .then(r => r.json())
  .then(console.log)
// If you see: {"success": true, "unread_count": X}  → Authenticated
// If you see: 401 error → Not authenticated
```

**Check 2: CSRF Token**
```javascript
// In console:
document.querySelector('meta[name="csrf-token"]').content
// Should return a long token string (not undefined)
```

**Check 3: Network request**
```
1. Open DevTools (F12)
2. Go to Network tab
3. Send a message
4. Look for request to /api/chat/send
5. Click it
6. Check Response tab
7. Should see: {"success": true, "message": {...}}
8. If error, note the error message
```

**Fix: Verify routes**
```bash
php artisan route:list | grep "api/chat"
# Should show all API routes for chat
```

---

### Issue: Admin dashboard not loading

**Check 1: Admin access**
```bash
# In Laravel console:
php artisan tinker
>>> auth()->user()->is_admin
# Should return: true (not false or null)
```

**Check 2: Route access**
```bash
# Test route
php artisan route:list | grep "admin/chat"
# Should show routes exist
```

**Check 3: Page loads but blank**
```javascript
// Open console (F12)
// Look for JavaScript errors (red X)
// Look for failed fetch requests
// Check Network tab for:
// - GET /api/chat/admin/conversations
// - Should return 200 with JSON data
```

**Check 4: Database data**
```bash
php artisan tinker
>>> \App\Models\ChatConversation::all()
# Should return existing conversations
# If empty, create test conversation
```

**Fix: Recreate admin session**
```
1. Log out
2. Clear cookies (F12 → Application)
3. Log back in
4. Try dashboard again
```

---

### Issue: Messages not updating in real-time

**Check 1: Auto-refresh working**
```javascript
// In console, on active chat:
// Watch Network tab
// Should see requests every 3 seconds to: /api/chat/{id}/messages
// If no requests, refresh not running
```

**Check 2: API returning data**
```bash
# Get conversation ID from URL or console
# Test endpoint directly:
curl http://localhost:8000/api/chat/1/messages \
  -H "X-CSRF-TOKEN: your_token_here"
# Should return JSON with messages array
```

**Check 3: JavaScript errors**
```
1. Open console (F12)
2. Any red errors? Note them
3. Check for network errors
4. Test in different browser
```

**Fix: Manual refresh**
```javascript
// In console:
loadMessages()
// Force load messages immediately
// Should update within 1 second
```

---

### Issue: Unread badges not updating

**Check 1: Badge endpoint**
```javascript
// In console:
fetch('/api/chat/unread-count')
  .then(r => r.json())
  .then(data => {
    console.log('Unread count:', data.unread_count)
    console.log('Badge should show:', data.unread_count)
  })
```

**Check 2: Badge DOM**
```javascript
// Check if badge element exists:
document.getElementById('chat-badge')
// Should return: <span id="chat-badge">...</span>
// If null, element doesn't exist
```

**Check 3: CSS display**
```javascript
// Check visibility:
const badge = document.getElementById('chat-badge')
window.getComputedStyle(badge).display
// Should return: "flex" or "block" (not "none")
```

---

## 🧪 Database Testing

### Check Tables Created
```bash
php artisan tinker

# Check structure
>>> Schema::getColumnListing('chat_conversations')
>>> Schema::getColumnListing('chat_messages')

# Should show all columns
```

### Create Test Data
```bash
php artisan tinker

# Create a test conversation
>>> $user = \App\Models\User::first()
>>> $conversation = \App\Models\ChatConversation::create([
    'user_id' => $user->id,
    'visitor_name' => $user->name,
    'visitor_email' => $user->email,
    'status' => 'active'
])

# Create test message
>>> \App\Models\ChatMessage::create([
    'conversation_id' => $conversation->id,
    'user_id' => $user->id,
    'sender_type' => 'user',
    'message' => 'Test message'
])

# Verify
>>> $conversation->messages()->count()  # Should return: 1
```

### Check Indexes
```bash
php artisan tinker
>>> DB::select("SHOW INDEX FROM chat_conversations")
>>> DB::select("SHOW INDEX FROM chat_messages")
# Should show multiple indexes for fast queries
```

---

## 📊 Performance Testing

### Load Test
```bash
# Simulate 100 concurrent users
# Use Apache Bench or similar:
ab -n 100 -c 10 http://localhost:8000/

# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Check response times
# Should be < 100ms per request
```

### Database Query Speed
```bash
php artisan tinker

# Test conversation query
>>> $start = microtime(true)
>>> \App\Models\ChatConversation::all()->load('messages')
>>> $time = microtime(true) - $start
>>> echo "Time: {$time}ms"
# Should be < 50ms
```

### API Response Time
```javascript
// In browser console:
const start = performance.now()
fetch('/api/chat/unread-count')
  .then(r => r.json())
  .then(() => {
    const time = performance.now() - start
    console.log(`Response time: ${time.toFixed(2)}ms`)
  })
// Should be < 100ms
```

---

## 🔐 Security Testing

### CSRF Token Test
```javascript
// Verify CSRF token exists:
const token = document.querySelector('meta[name="csrf-token"]').content
console.log('Token:', token)  // Should show 64-char string
console.log('Length:', token.length)  // Should be 64
```

### Authorization Test
```bash
# Test as guest (no auth):
curl http://localhost:8000/api/chat/unread-count
# Should return: 401 Unauthorized

# Test as user:
# (after logging in, token is in cookie)
curl http://localhost:8000/api/chat/unread-count
# Should return: {"success": true, "unread_count": X}

# Test as non-admin accessing admin endpoint:
curl http://localhost:8000/admin/chat
# Should return: 403 Forbidden
```

### Input Validation Test
```bash
# Try empty message:
curl -X POST http://localhost:8000/api/chat/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: token" \
  -d '{"conversation_id": 1, "message": ""}'
# Should return error

# Try very long message (> 5000 chars):
curl -X POST http://localhost:8000/api/chat/send \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: token" \
  -d "{\"conversation_id\": 1, \"message\": \"$(printf 'A%.0s' {1..6000})\"}"
# Should return validation error
```

---

## 📱 Device Testing

### Desktop Testing
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

### Mobile Testing
- [ ] iPhone (Safari)
- [ ] Android (Chrome)
- [ ] iPad (Safari)
- [ ] Android tablet

### Responsive Sizes
```
- Desktop: 1920x1080 ✓
- Laptop: 1366x768 ✓
- Tablet: 768x1024 ✓
- Phone: 375x667 ✓
- Small phone: 320x568 ✓
```

---

## 🛠️ Common Fixes

### Problem: "Unauthorized" on API call
**Solution:**
```bash
# User not authenticated
1. Check if logged in
2. Check if session expired
3. Clear cookies and login again
php artisan cache:clear
```

### Problem: "Table doesn't exist"
**Solution:**
```bash
# Migrations not run
php artisan migrate

# Or check migration status
php artisan migrate:status
```

### Problem: Routes not found
**Solution:**
```bash
# Clear route cache
php artisan route:cache
php artisan config:cache

# Or disable caching for development
# In .env: APP_DEBUG=true
```

### Problem: CSRF token mismatch
**Solution:**
```
1. Make sure meta tag exists in HTML:
   <meta name="csrf-token" content="{{ csrf_token() }}">

2. Verify token in request header:
   X-CSRF-TOKEN: value_from_meta_tag

3. Check if session is valid
```

### Problem: Messages not persisting
**Solution:**
```bash
# Check database connection
php artisan migrate:status

# Verify data is actually saved
php artisan tinker
>>> \App\Models\ChatMessage::count()

# Check Laravel logs
tail storage/logs/laravel.log
```

---

## ✅ Final Verification

Run this complete check:

```bash
#!/bin/bash

echo "🔍 Chat System Health Check"
echo "=============================="

# 1. Database tables
echo "✓ Checking database tables..."
php artisan tinker <<'EOF'
if (Schema::hasTable('chat_conversations') && Schema::hasTable('chat_messages')) {
    echo "  ✓ Tables exist\n";
} else {
    echo "  ✗ Tables missing - run: php artisan migrate\n";
}
EOF

# 2. Routes
echo "✓ Checking routes..."
if php artisan route:list | grep -q "api/chat"; then
    echo "  ✓ Chat routes registered"
else
    echo "  ✗ Routes not found"
fi

# 3. Files
echo "✓ Checking files..."
[ -f "app/Models/ChatConversation.php" ] && echo "  ✓ ChatConversation model"
[ -f "app/Models/ChatMessage.php" ] && echo "  ✓ ChatMessage model"
[ -f "app/Http/Controllers/ChatController.php" ] && echo "  ✓ ChatController"
[ -f "resources/views/components/chat-widget.blade.php" ] && echo "  ✓ Chat widget view"
[ -f "resources/views/admin/chat/index.blade.php" ] && echo "  ✓ Admin dashboard view"

# 4. Cache
echo "✓ Clearing cache..."
php artisan config:cache
php artisan cache:clear
echo "  ✓ Cache cleared"

echo ""
echo "✅ Health check complete!"
echo ""
echo "Next steps:"
echo "1. php artisan serve"
echo "2. Visit http://localhost:8000"
echo "3. Look for 💬 button at bottom-right"
echo "4. Log in as admin and visit /admin/chat"
```

Save as `check-chat.sh` and run:
```bash
chmod +x check-chat.sh
./check-chat.sh
```

---

## 📞 When to Escalate

If you encounter issues not covered here:

1. **Check logs:**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

2. **Enable debug mode:**
   ```
   In .env: APP_DEBUG=true
   ```

3. **Gather information:**
   - Error message (exact text)
   - Steps to reproduce
   - Browser console errors
   - Network request details
   - Laravel log entries

4. **Document and report:**
   - Include all above information
   - What you already tried
   - Expected vs actual behavior

---

This completes your testing guide. Your Tawk.to chat system is ready for production! 🚀

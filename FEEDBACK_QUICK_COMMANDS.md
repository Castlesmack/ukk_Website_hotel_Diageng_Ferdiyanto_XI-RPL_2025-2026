# Feedback System - Quick Command Reference

## 🎯 Setup Commands

### Run Migration (Create Table)
```bash
php artisan migrate
```

### Undo Migration
```bash
php artisan migrate:rollback
```

### Reset Database
```bash
php artisan migrate:reset
```

### Fresh Install
```bash
php artisan migrate:fresh
```

---

## 🧹 Cache & Clear Commands

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Single Commands
```bash
php artisan config:clear    # Clear config cache
php artisan route:clear     # Clear route cache
php artisan view:clear      # Clear view cache
```

---

## 🔍 Debugging Commands

### List All Routes
```bash
php artisan route:list
```

### Filter Feedback Routes Only
```bash
php artisan route:list | grep feedback
```

### Show Database Tables
```bash
php artisan migrate:status
```

### Tinker Shell (Query Database)
```bash
php artisan tinker
> Feedback::count()
> Feedback::open()->get()
> User::find(1)->feedbacks
```

---

## 🗄️ Database Commands (SQL)

### Check Table Structure
```sql
DESCRIBE feedbacks;
```

### View All Feedback
```sql
SELECT * FROM feedbacks ORDER BY created_at DESC;
```

### Count by Status
```sql
SELECT status, COUNT(*) as count FROM feedbacks GROUP BY status;
```

### Count by Channel
```sql
SELECT channel, COUNT(*) as count FROM feedbacks GROUP BY channel;
```

### Open Feedback Only
```sql
SELECT * FROM feedbacks WHERE status = 'open';
```

### Feedback with Relationships
```sql
SELECT f.id, u.name as from_user, r.name as responded_by, f.message, f.status
FROM feedbacks f
LEFT JOIN users u ON f.user_id = u.id
LEFT JOIN users r ON f.responder_id = r.id
ORDER BY f.created_at DESC;
```

### Delete Test Data
```sql
DELETE FROM feedbacks WHERE id > 0;  -- Delete all
DELETE FROM feedbacks WHERE status = 'closed';  -- Delete closed
```

---

## 🧪 Testing Commands

### Run Unit Tests
```bash
php artisan test --filter=FeedbackTest
```

### Run All Tests
```bash
php artisan test
```

### Run with Coverage
```bash
php artisan test --coverage
```

---

## 👥 Model Commands (PHP Tinker)

### Open Tinker Shell
```bash
php artisan tinker
```

### In Tinker - Create Feedback
```php
> $feedback = Feedback::create([
    'user_id' => 1,
    'message' => 'Test feedback',
    'channel' => 'web'
  ]);
```

### In Tinker - Get Feedback
```php
> Feedback::find(1)
> Feedback::all()
> Feedback::open()->get()
> Feedback::where('user_id', 1)->get()
```

### In Tinker - Get Relationships
```php
> $feedback = Feedback::find(1)
> $feedback->user        # Get submitter
> $feedback->responder   # Get who responded
> $feedback->booking     # Get related booking
```

### In Tinker - Update Feedback
```php
> $f = Feedback::find(1)
> $f->response = "Thank you"
> $f->status = "answered"
> $f->responder_id = 2
> $f->save()
```

### In Tinker - Delete Feedback
```php
> Feedback::find(1)->delete()
> Feedback::where('status', 'closed')->delete()
```

---

## 🔐 Authentication Commands

### Check Current User (In Tinker)
```php
> Auth::user()
> Auth::id()
> Auth::check()
```

### Check User Role
```php
> $user = User::find(1)
> $user->role
```

### List All Users
```php
> User::all()->pluck('name', 'id')
> User::where('role', 'admin')->get()
```

---

## 🎨 Artisan Make Commands (For Future)

### Create Event (for notifications)
```bash
php artisan make:event FeedbackSubmitted
php artisan make:event FeedbackResponded
```

### Create Listener
```bash
php artisan make:listener SendFeedbackNotification
```

### Create Mailable
```bash
php artisan make:mail FeedbackResponse
```

### Create Job
```bash
php artisan make:job ProcessFeedback
```

---

## 📊 Database Query Examples

### Statistics Query
```sql
SELECT 
  COUNT(*) as total_feedback,
  COUNT(CASE WHEN status='open' THEN 1 END) as open_count,
  COUNT(CASE WHEN status='answered' THEN 1 END) as answered_count,
  COUNT(CASE WHEN status='closed' THEN 1 END) as closed_count,
  AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_response_hours
FROM feedbacks;
```

### Feedback by User
```sql
SELECT u.name, COUNT(f.id) as feedback_count
FROM users u
LEFT JOIN feedbacks f ON u.id = f.user_id
GROUP BY u.id, u.name
ORDER BY feedback_count DESC;
```

### Feedback by Receptionist
```sql
SELECT u.name, COUNT(f.id) as responses
FROM users u
LEFT JOIN feedbacks f ON u.id = f.responder_id
WHERE u.role = 'receptionist'
GROUP BY u.id, u.name
ORDER BY responses DESC;
```

---

## 🚀 Deployment Commands

### Check Requirements
```bash
php artisan about
```

### Optimize for Production
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
```

### Create .env from .env.example
```bash
cp .env.example .env
```

### Generate App Key
```bash
php artisan key:generate
```

### Setup Production Database
```bash
php artisan migrate --env=production
php artisan migrate --force  # Skip confirmation
```

---

## 🔄 Workflow Commands

### Quick Setup
```bash
# 1. Run migration
php artisan migrate

# 2. Clear caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear

# 3. Serve application
php artisan serve
```

### Development with Hot Reload
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### Check if Everything Works
```bash
php artisan route:list | grep feedback
php artisan tinker
> Feedback::count()  # Should return 0 initially
```

---

## 📝 Common Workflows

### Create Test Feedback
```bash
php artisan tinker
> Feedback::create([
    'user_id' => 1,
    'message' => 'Test message',
    'channel' => 'web'
  ])
```

### Respond to Feedback
```bash
> $f = Feedback::find(1)
> $f->update([
    'response' => 'Thank you for feedback',
    'status' => 'answered',
    'responder_id' => 2
  ])
```

### Verify Access
```bash
> $feedback = Feedback::find(1)
> Gate::allows('view', $feedback)  # Check authorization
> Gate::denies('view', $feedback)
```

---

## 🆘 Troubleshooting Commands

### Check Error Logs
```bash
tail -f storage/logs/laravel.log
```

### Check Database Connection
```bash
php artisan tinker
> DB::connection()->getPdo()
> DB::select('SELECT 1')  # Test query
```

### Verify Routes
```bash
php artisan route:list --name=feedback
```

### Test Policy
```bash
php artisan tinker
> $user = User::find(1)
> $feedback = Feedback::find(1)
> $user->can('view', $feedback)
```

---

## 📱 File Quick Paths

```
Models:          app/Models/Feedback.php
Controller:      app/Http/Controllers/FeedbackController.php
Policy:          app/Policies/FeedbackPolicy.php
Routes:          routes/web.php
Migration:       database/migrations/2024_01_23_create_feedbacks_table.php
Views:           resources/views/feedback/
  - index.blade.php
  - create.blade.php
  - show.blade.php
  - edit.blade.php
```

---

**Quick Command Cheat Sheet**
**Bookmark this file for quick reference during development**
**Last Updated**: January 23, 2026

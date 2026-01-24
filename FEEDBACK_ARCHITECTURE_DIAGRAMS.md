# Feedback System - Architecture & Flow Diagrams

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    FEEDBACK SYSTEM                          │
└─────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│                    USER ROLES & ACCESS                         │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  👤 GUEST                 💼 RECEPTIONIST          👨‍💼 ADMIN       │
│  ├─ View own only        ├─ View all            ├─ View all    │
│  ├─ Create feedback      ├─ Respond to all      ├─ Full control│
│  ├─ Close own            ├─ Update status       ├─ Delete      │
│  └─ /feedback/*          └─ /reception/feedback└─ /admin/     │
│                                                   feedback/*    │
│                                                                │
└────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│                   DATABASE SCHEMA                              │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  feedbacks TABLE                                              │
│  ├── id (PK)                                                 │
│  ├── user_id (FK) ────────→ users.id                        │
│  ├── booking_id (FK) ──────→ bookings.id                    │
│  ├── responder_id (FK) ────→ users.id                       │
│  ├── channel (enum)                                          │
│  ├── message (text)                                          │
│  ├── response (text)                                         │
│  ├── status (enum)                                           │
│  ├── created_at (timestamp)                                  │
│  └── updated_at (timestamp)                                  │
│                                                                │
└────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│                   MVC STRUCTURE                                │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  MODEL                 CONTROLLER            VIEW             │
│  ┌──────────────────┐  ┌─────────────────┐  ┌──────────────┐ │
│  │  Feedback.php    │  │ Feedback        │  │ index.blade  │ │
│  │  ├─ Relations    │  │ Controller.php  │  │ create.blade │ │
│  │  ├─ Scopes       │  │ ├─ index()      │  │ show.blade   │ │
│  │  └─ Attributes   │  │ ├─ create()     │  │ edit.blade   │ │
│  │                  │  │ ├─ store()      │  │              │ │
│  │  User.php        │  │ ├─ show()       │  │              │ │
│  │  └─ feedbacks()  │  │ ├─ edit()       │  │              │ │
│  │                  │  │ ├─ update()     │  │              │ │
│  │  Booking.php     │  │ ├─ close()      │  │              │ │
│  │  └─ feedbacks()  │  │ ├─ stats()      │  │              │ │
│  │                  │  │ └─ recent()     │  │              │ │
│  └──────────────────┘  └─────────────────┘  └──────────────┘ │
│                                                                │
└────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────┐
│                   AUTHORIZATION LAYER                          │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  FeedbackPolicy.php                                           │
│  ├─ viewAny()    → All authenticated users                   │
│  ├─ view()       → Guest: own only | Staff: all              │
│  ├─ create()     → All authenticated users                   │
│  ├─ update()     → Admin & Receptionist only                 │
│  ├─ delete()     → Admin only                                │
│  └─ forceDelete()→ Admin only                                │
│                                                                │
│  Middleware: auth, admin, receptionist                       │
│  Gates & Policies applied to all routes                      │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

---

## 📊 Feedback Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                     FEEDBACK LIFECYCLE                          │
└─────────────────────────────────────────────────────────────────┘

GUEST WORKFLOW:
──────────────

  1. Visit /feedback/create
         ↓
  2. Fill form
     ├─ Message (required)
     ├─ Booking (optional)
     └─ Channel (web/email/livechat)
         ↓
  3. Click "Send Message"
         ↓
  4. Feedback created with status="open"
         ↓
  5. Guest sees in /feedback list
         ↓
  6. Guest waits for response...
         ↓
  7. Response appears in feedback detail
         ↓
  8. Guest clicks "Close Message"
         ↓
  9. Status changes to "closed"

STAFF WORKFLOW:
──────────────

  1. Admin/Receptionist logs in
         ↓
  2. Navigate to /admin/feedback or /reception/feedback
         ↓
  3. See list of all guest feedback
         ↓
  4. Click on feedback item
         ↓
  5. View guest message
         ↓
  6. Click "Send Response" or "Edit Response"
         ↓
  7. Fill response form
         ↓
  8. Select status
     ├─ "answered" (still open for guest to close)
     └─ "closed" (mark as resolved)
         ↓
  9. Click "Send Response"
         ↓
 10. Response saved with responder_id
         ↓
 11. Guest notified (future: email)
         ↓
 12. Guest can see response and close

STATUS FLOW:
────────────

  open ──────→ answered ──────→ closed
   ↑           ↑               ↑
   │           │               │
   └─ initial  └─ staff        └─ guest or
       state       responds        staff closes
```

---

## 🔄 Request/Response Cycle

```
┌─────────────────────────────────────────────────────────────────┐
│                    HTTP REQUEST FLOW                            │
└─────────────────────────────────────────────────────────────────┘

CREATE FEEDBACK:
────────────────

  User visits /feedback/create
         ↓
  FeedbackController@create
         ↓
  Returns create.blade.php form
         ↓
  User fills and submits
         ↓
  POST /feedback
         ↓
  FeedbackController@store
    ├─ Validates input
    ├─ Creates Feedback record
    └─ Redirects to show
         ↓
  Feedback saved to database
         ↓
  User redirected to /feedback/{id}


VIEW FEEDBACK:
──────────────

  GET /feedback/{id}
         ↓
  FeedbackController@show
    ├─ Load Feedback with relations
    ├─ Check authorization (Policy)
    └─ Return show.blade.php
         ↓
  User sees feedback detail


RESPOND TO FEEDBACK:
────────────────────

  GET /feedback/{id}/edit
         ↓
  FeedbackController@edit
    ├─ Check authorization
    └─ Return edit.blade.php
         ↓
  Staff fills response form
         ↓
  PUT /feedback/{id}
         ↓
  FeedbackController@update
    ├─ Validates input
    ├─ Updates Feedback
    │   ├─ response text
    │   ├─ status
    │   └─ responder_id
    └─ Redirects to show
         ↓
  Response saved
         ↓
  Guest can now see response
```

---

## 🔐 Authorization Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                   POLICY AUTHORIZATION                          │
└─────────────────────────────────────────────────────────────────┘

REQUEST ARRIVES:
────────────────

  GET /feedback/{id}
         ↓
  Middleware checks auth
    ├─ Is user logged in? YES → continue
    └─ Is user logged in? NO → redirect to login
         ↓
  Controller loads Feedback
         ↓
  Policy checks @show()
         ↓
  GUEST?
    ├─ YES → Is feedback->user_id == auth->id?
    │         ├─ YES → Allow view
    │         └─ NO → Abort 403 Forbidden
    └─ NO → Continue
         ↓
  RECEPTIONIST or ADMIN?
    ├─ YES → Allow view
    └─ NO → Abort 403 Forbidden
         ↓
  Policy passes
         ↓
  Controller returns view


RESPONSE AUTHORIZATION:
───────────────────────

  PUT /feedback/{id}  (send response)
         ↓
  Policy checks @update()
         ↓
  Is user ADMIN or RECEPTIONIST?
    ├─ YES → Allow update
    └─ NO → Abort 403 Forbidden
         ↓
  Can update:
    ├─ response text
    ├─ status
    └─ responder_id
```

---

## 📍 Route Structure

```
┌─────────────────────────────────────────────────────────────────┐
│                      ROUTES MAP                                 │
└─────────────────────────────────────────────────────────────────┘

/feedback (Guest, Receptionist, Admin)
├── GET  /feedback
│   │    FeedbackController@index
│   │    View own (guest) or all (staff)
│   │
├── GET  /feedback/create
│   │    FeedbackController@create
│   │    Show create form
│   │
├── POST /feedback
│   │    FeedbackController@store
│   │    Save new feedback
│   │
├── GET  /feedback/{id}
│   │    FeedbackController@show
│   │    View feedback detail
│   │
├── GET  /feedback/{id}/edit
│   │    FeedbackController@edit
│   │    Show response form (staff only)
│   │
├── PUT  /feedback/{id}
│   │    FeedbackController@update
│   │    Save response (staff only)
│   │
└── POST /feedback/{id}/close
        FeedbackController@close
        Mark as closed

/reception/feedback (Receptionist only)
├── GET  /reception/feedback
├── GET  /reception/feedback/{id}
├── GET  /reception/feedback/{id}/edit
├── PUT  /reception/feedback/{id}
└── POST /reception/feedback/{id}/close

/admin/feedback (Admin only)
├── GET  /admin/feedback
├── GET  /admin/feedback/{id}
├── GET  /admin/feedback/{id}/edit
├── PUT  /admin/feedback/{id}
└── POST /admin/feedback/{id}/close

/api/feedback (Guest, Receptionist, Admin)
├── GET  /api/feedback/stats
│        JSON: { total, open, answered, closed }
│
└── GET  /api/feedback/recent/{limit}
         JSON: Array of recent feedback items
```

---

## 🎯 Data Flow Example

```
SCENARIO: Guest sends feedback, Admin responds

┌──────────────┐
│ Guest User   │
└──────┬───────┘
       │
       │ 1. Visits /feedback/create
       │ 2. Fills form: "Room was too noisy"
       ↓
┌─────────────────────────────────────────┐
│ FeedbackController@store()              │
│ - Validates input                       │
│ - Creates Feedback record:              │
│   {                                     │
│     user_id: 5,                         │
│     message: "Room was too noisy",      │
│     channel: "web",                     │
│     status: "open",                     │
│     booking_id: 12                      │
│   }                                     │
└──────┬──────────────────────────────────┘
       │
       │ INSERT into feedbacks
       ↓
┌─────────────────────────────────────┐
│ Database (feedbacks table)           │
│ id: 1                               │
│ user_id: 5                          │
│ message: "Room was too noisy"       │
│ status: "open"                      │
│ created_at: 2026-01-23 10:30:00    │
└──────┬──────────────────────────────┘
       │
       │ 3. Admin logs in, visits /admin/feedback
       ↓
┌──────────────────────────────────┐
│ FeedbackController@index()        │
│ - Loads all feedback              │
│ - Shows list to admin             │
└──────┬───────────────────────────┘
       │
       │ 4. Admin clicks on feedback #1
       ↓
┌──────────────────────────────────┐
│ FeedbackController@show()         │
│ - Loads Feedback with relations   │
│ - Checks authorization (allowed)  │
│ - Shows detail page               │
└──────┬───────────────────────────┘
       │
       │ 5. Admin types response: "We apologize..."
       │ 6. Admin clicks "Send Response"
       ↓
┌─────────────────────────────────────────┐
│ FeedbackController@update()             │
│ - Validates response text               │
│ - Updates Feedback record:              │
│   {                                     │
│     response: "We apologize...",        │
│     status: "answered",                 │
│     responder_id: 2,                    │
│     updated_at: now()                   │
│   }                                     │
└──────┬──────────────────────────────────┘
       │
       │ UPDATE feedbacks SET...
       ↓
┌─────────────────────────────────┐
│ Database (feedbacks table)       │
│ id: 1                           │
│ response: "We apologize..."     │
│ status: "answered"              │
│ responder_id: 2                 │
│ updated_at: 2026-01-23 11:00:00│
└──────┬──────────────────────────┘
       │
       │ 7. Guest logs in, visits /feedback
       ↓
┌──────────────────────────────────┐
│ FeedbackController@index()        │
│ - Loads Guest's feedback          │
│ - Shows feedback #1 with response │
└──────┬───────────────────────────┘
       │
       │ 8. Guest clicks on feedback #1
       ↓
┌──────────────────────────────────┐
│ FeedbackController@show()         │
│ - Shows response to guest         │
│ - Shows "Close Message" button    │
└──────┬───────────────────────────┘
       │
       │ 9. Guest clicks "Close Message"
       ↓
┌─────────────────────────────────────────┐
│ FeedbackController@close()              │
│ - Updates Feedback:                     │
│   { status: "closed" }                  │
└──────┬──────────────────────────────────┘
       │
       │ UPDATE feedbacks SET status="closed"
       ↓
┌─────────────────────────────────┐
│ Feedback #1 is now CLOSED       │
│ Both guest & admin can see it   │
│ in their feedback lists         │
└─────────────────────────────────┘
```

---

## 📈 Performance Considerations

```
Indexes Created:
┌────────────────────────────────────────┐
│ user_id    - Fast guest lookup          │
│ booking_id - Fast booking association   │
│ status     - Fast status filtering      │
└────────────────────────────────────────┘

Query Optimization:
┌────────────────────────────────────────┐
│ WITH relationships:                     │
│ Feedback::with(['user', 'responder'])   │
│                                         │
│ Pagination:                             │
│ Feedback::paginate(10)                  │
│                                         │
│ Filtering:                              │
│ Feedback::open()->get()                 │
│ Feedback::where('status', 'open')       │
└────────────────────────────────────────┘
```

---

**Architecture Diagram Version**: 1.0
**Last Updated**: January 23, 2026
**Status**: Complete and Documented

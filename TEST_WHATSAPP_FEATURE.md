# WhatsApp Message Feature - Quick Test Guide

## Pre-Testing Setup

### 1. Run Migration
```bash
cd /Users/macmini/SantosWork/Apps/PamulihanAppV2
php artisan migrate
```

### 2. Clear Cache
```bash
php artisan optimize:clear
```

### 3. Verify WhatsApp API Configuration
Check your `.env` file has:
```env
WHATSAPP_API_URL=your_api_url
WHATSAPP_API_KEY=your_api_key
```

---

## Quick Test Scenarios

### Test 1: Basic Message Sending ✅

**Steps**:
1. Login to Filament admin panel
2. Navigate to "Communication → WhatsApp Messages"
3. Click "Send New Message"
4. Enter phone: `08123456789`
5. Watch it auto-convert to: `628123456789`
6. Enter message: "This is a test message"
7. Click "Create"

**Expected Result**:
- ✅ Success notification appears
- ✅ Redirected to message list
- ✅ Message appears with "Sent" status (green badge)
- ✅ Badge counter on navigation increases by 1

---

### Test 2: Phone Number Validation ⚠️

**Steps**:
1. Click "Send New Message"
2. Try these phone numbers:

| Input | Expected Behavior |
|-------|------------------|
| `12345` | ❌ Validation error: too short |
| `08123` | ❌ Validation error: too short |
| `72123456789` | ❌ Validation error: must start with 0 or 62 |
| `08123456789` | ✅ Auto-converts to 628123456789 |
| `628123456789` | ✅ Accepted as-is |
| `62 812 3456 789` | ✅ Spaces removed |
| `62-812-3456-789` | ✅ Dashes removed |
| `+628123456789` | ✅ Plus sign removed |

---

### Test 3: Character Counter 📝

**Steps**:
1. Click "Send New Message"
2. In message field, start typing
3. Watch helper text below field

**Expected Result**:
- ✅ Shows "Characters: X / 1000"
- ✅ Updates in real-time as you type
- ✅ Cannot exceed 1000 characters

---

### Test 4: View Message Details 👁️

**Steps**:
1. Go to message list
2. Click on any message row

**Expected Result**:
- ✅ Opens view page
- ✅ Shows phone number (read-only)
- ✅ Shows full message (read-only)
- ✅ Shows status badge
- ✅ Shows sent_at timestamp
- ✅ "Back to Messages" button visible
- ✅ Delete button visible

---

### Test 5: Table Search & Filter 🔍

**Steps**:
1. Go to message list
2. Use search box to search for phone number
3. Click "Filter" button
4. Select status: "Sent"
5. Clear and try date range filter

**Expected Result**:
- ✅ Search filters results correctly
- ✅ Status filter works
- ✅ Date range filter works
- ✅ Can combine multiple filters

---

### Test 6: Message Preview & Tooltip 💬

**Steps**:
1. Send a message longer than 50 characters:
   ```
   This is a very long message that exceeds fifty characters and should be truncated in the table view
   ```
2. Go to message list
3. Hover over the message preview

**Expected Result**:
- ✅ Table shows: "This is a very long message that exceeds fifty..." (truncated)
- ✅ Tooltip shows full message on hover

---

### Test 7: Failed Message Handling ❌

**Note**: This test requires intentionally causing a failure.

**Option A - Invalid API Key (Temporary)**:
1. Temporarily change `WHATSAPP_API_KEY` in `.env` to invalid value
2. Send a message
3. Restore correct API key

**Option B - Disconnect Network** (if testing locally):
1. Temporarily disable network
2. Send message
3. Re-enable network

**Expected Result**:
- ✅ Error notification appears (persistent)
- ✅ Message saved with "Failed" status (red badge with X icon)
- ✅ Error message stored in database
- ✅ View page shows error message field

---

### Test 8: Copy Phone Number 📋

**Steps**:
1. Go to message list
2. Look at phone number column
3. Click on any phone number

**Expected Result**:
- ✅ Phone number is copied to clipboard
- ✅ Tooltip shows "Click to copy"

---

### Test 9: Navigation Badge 🔢

**Steps**:
1. Check the "WhatsApp Messages" menu item
2. Note the badge number
3. Send a new message successfully
4. Check badge again

**Expected Result**:
- ✅ Badge shows count of sent messages (not failed)
- ✅ Badge is green color
- ✅ Count increases after sending new message

---

### Test 10: Bulk Delete 🗑️

**Steps**:
1. Go to message list
2. Select multiple messages using checkboxes
3. Click bulk actions dropdown
4. Select "Delete selected"
5. Confirm deletion

**Expected Result**:
- ✅ Can select multiple rows
- ✅ Bulk delete option appears
- ✅ Confirmation dialog shows
- ✅ Selected messages are deleted

---

## Database Verification

After sending a few test messages, verify the database:

```bash
php artisan tinker
```

Then run:
```php
// Count all messages
\App\Models\WhatsAppMessage::count();

// Get last 5 messages
\App\Models\WhatsAppMessage::latest()->take(5)->get();

// Count sent messages
\App\Models\WhatsAppMessage::sent()->count();

// Count failed messages
\App\Models\WhatsAppMessage::failed()->count();

// Check a specific message
$message = \App\Models\WhatsAppMessage::first();
$message->isSent(); // true or false
$message->message_preview; // truncated version
```

---

## API Integration Verification

Test the WhatsAppService integration:

```bash
php artisan tinker
```

```php
$service = app(\App\Services\WhatsAppService::class);

// Test validation
$service->validatePhoneNumber('08123456789'); // should return true
$service->validatePhoneNumber('12345'); // should return false

// Test actual send (will hit real API)
$service->send('628123456789', 'Test message from tinker');
```

---

## Log Verification

Check logs for proper logging:

```bash
tail -f storage/logs/laravel.log
```

Send a message and watch for:
- ✅ Info log: "WhatsApp message sent successfully"
- ✅ Or Error log: "WhatsApp Service Exception" (if failed)

---

## Performance Check

If you have many messages, verify indexes are working:

```bash
php artisan tinker
```

```php
// These should be fast even with many records
\App\Models\WhatsAppMessage::where('phone_number', '628123456789')->get();
\App\Models\WhatsAppMessage::where('status', 'sent')->get();
\App\Models\WhatsAppMessage::whereDate('sent_at', today())->get();
```

---

## UI/UX Verification Checklist

Access the feature and verify:

**Navigation**:
- [ ] "WhatsApp Messages" appears in "Communication" group
- [ ] Icon is chat-bubble-left-right
- [ ] Badge shows sent message count
- [ ] Badge is green

**Create Page**:
- [ ] Phone field has helper text
- [ ] Phone field auto-formats on blur
- [ ] Message field has character counter
- [ ] Counter updates in real-time
- [ ] Form validation works
- [ ] Success notification appears
- [ ] Redirects to index after send

**List Page**:
- [ ] "Send New Message" button with paper airplane icon
- [ ] Table shows all columns correctly
- [ ] Status badges have correct colors/icons
- [ ] Phone numbers are copyable
- [ ] Search works
- [ ] Filters work
- [ ] View action available

**View Page**:
- [ ] All fields displayed correctly
- [ ] Fields are read-only
- [ ] Status badge shows
- [ ] Error message visible (if failed)
- [ ] Back button works
- [ ] Delete button works

---

## Common Issues & Quick Fixes

### Issue: Navigation not showing
```bash
php artisan optimize:clear
php artisan filament:cache-components
```

### Issue: Validation not working
- Check Livewire is working: test any live validation on other forms
- Clear browser cache

### Issue: Character counter not updating
- This uses Livewire live updates
- Check browser console for errors
- Verify no JavaScript conflicts

### Issue: Messages not sending
1. Check `.env` configuration
2. Test API directly with curl/Postman
3. Check Laravel logs
4. Verify WhatsAppService is working

### Issue: Phone auto-format not working
- This triggers `onBlur` (when field loses focus)
- Click outside the phone field to trigger
- Check browser console for Livewire errors

---

## Success Criteria ✅

Your implementation is successful if:

1. ✅ Migration runs without errors
2. ✅ Can create new messages
3. ✅ Phone numbers validate correctly
4. ✅ Phone numbers auto-format
5. ✅ Character counter works
6. ✅ Messages send via WhatsApp API
7. ✅ Success messages appear in list
8. ✅ Failed messages are logged with errors
9. ✅ Can view message details
10. ✅ Can search and filter messages
11. ✅ Navigation badge shows correct count
12. ✅ All UI elements display correctly

---

## Next Steps After Testing

Once all tests pass:

1. **Document for your team**:
   - Share the WHATSAPP_MESSAGE_FEATURE_README.md
   - Train team members on usage

2. **Monitor in production**:
   - Watch Laravel logs for errors
   - Monitor message success/failure rates
   - Check API rate limits

3. **Consider enhancements**:
   - Add message templates
   - Implement bulk sending
   - Add scheduling feature
   - Create analytics dashboard

---

## Need Help?

If tests fail:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console
3. Verify database migration completed
4. Verify WhatsApp API credentials
5. Test WhatsAppService independently

---

**Happy Testing! 🎉**

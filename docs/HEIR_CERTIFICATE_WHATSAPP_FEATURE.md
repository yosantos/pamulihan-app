# Heir Certificate WhatsApp Messaging Feature

## Overview
This feature allows administrators to send WhatsApp notifications directly from the Heir Certificate Resource table in Filament. Users can notify applicants when their heir certificate is ready for pickup.

## How to Use

### 1. Access the Feature
- Navigate to **Certificates > Heir Certificates** in the Filament admin panel
- Locate the certificate you want to send a notification for
- Click the **Actions** dropdown (three dots icon) on the certificate row

### 2. Send WhatsApp Message
- Click **"Send WhatsApp Message"** from the dropdown
- A modal will appear with the following options:

#### Form Fields:
1. **Campaign Template**: Select the message template (defaults to "Heir Certificate Ready")
2. **Phone Number**: The recipient's phone number (auto-filled from certificate data)
3. **Variables from Certificate**: Shows the data that will be used in the message
4. **Message Preview**: Live preview of the final message with all variables replaced

### 3. Review and Send
- Review the message preview to ensure all information is correct
- Click **"Send Message"** to send the WhatsApp notification
- You will receive a success or error notification

## Campaign Template

### "Heir Certificate Ready" Template
```
[Name_Company]. Hello [applicant_name],

Your Heir Certificate with number [certificate_number] has been processed and is ready for pickup.

Certificate Date: [certificate_date]
Status: [status]

Please contact our office to collect your certificate.

Thank you.
```

### Variables Used:
- **applicant_name**: The name of the applicant from the certificate
- **certificate_number**: The formatted certificate number (e.g., 001/2025)
- **certificate_date**: The certificate issuance date (formatted as "d M Y")
- **status**: The current status of the certificate (On Progress/Completed)

## Features

### Automatic Phone Number Validation
- Phone numbers are validated to ensure they follow Indonesian format
- Accepts both `08xxx` and `62xxx` formats
- Phone numbers starting with `08` are automatically converted to `62xxx`

### Action Availability
- The action is **disabled** if the certificate does not have a phone number
- Hover over the disabled action to see a tooltip explaining why it's disabled

### Message Tracking
- All sent messages are automatically logged in the `whatsapp_messages` table
- You can track message status, delivery, and history
- Campaign usage count is automatically incremented

### Error Handling
- If the campaign is not found, you'll receive an error notification
- If the message fails to send, you'll receive a detailed error message
- All errors are displayed as persistent notifications for visibility

## Action Position in Dropdown

The actions are ordered as follows:
1. **View** - View certificate details
2. **Edit** - Edit certificate information
3. **Send WhatsApp Message** - Send notification (NEW)
4. **Mark as Completed** / **Mark as On Progress** - Change status
5. **Delete** - Remove certificate

## Technical Details

### Files Modified:
1. **app/Filament/Resources/HeirCertificateResource.php**
   - Added "Send WhatsApp Message" action to the table actions
   - Integrated with Campaign facade for sending messages
   - Added form with campaign selection and message preview

2. **database/seeders/CommonCampaignsSeeder.php**
   - Added "Heir Certificate Ready" campaign template
   - Campaign is active by default

### Dependencies:
- `App\Models\WhatsAppCampaign` - Campaign model
- `App\Services\TemplateVariableParser` - Variable replacement service
- `App\Facades\Campaign` - Campaign sending facade
- Filament Forms and Tables components

### Database Tables:
- `whatsapp_campaigns` - Stores campaign templates
- `whatsapp_messages` - Logs all sent messages (automatic)

## Re-seeding Campaigns

If you need to update or re-create the campaign:

```bash
php artisan db:seed --class=CommonCampaignsSeeder
```

This will update existing campaigns or create new ones without duplicating.

## Customization

### Changing the Template
1. Navigate to the WhatsApp Campaigns resource in Filament
2. Find "Heir Certificate Ready" campaign
3. Edit the template as needed
4. Save changes

### Adding New Variables
1. Update the campaign template with new `[variable_name]` placeholders
2. Update the variables array in the campaign
3. Modify the `$variables` array in HeirCertificateResource.php to include the new data

### Using Different Campaigns
Users can select any active campaign from the dropdown. The default is "Heir Certificate Ready", but other campaigns can be used if they have compatible variables.

## Troubleshooting

### Action is Disabled
- **Cause**: The certificate doesn't have a phone number set
- **Solution**: Edit the certificate and add a phone number

### Message Preview Shows Placeholders
- **Cause**: Variables are not being replaced correctly
- **Solution**: Check that all required variables are available in the certificate record

### Campaign Not Found
- **Cause**: The campaign hasn't been seeded or was deleted
- **Solution**: Run the seeder: `php artisan db:seed --class=CommonCampaignsSeeder`

### Message Not Sending
- **Cause**: WhatsApp API configuration or network issues
- **Solution**: Check WhatsApp API credentials and connection settings

## Best Practices

1. **Verify Phone Numbers**: Always ensure phone numbers are correctly formatted before sending
2. **Review Preview**: Always review the message preview before sending
3. **Test First**: Test with your own number before sending to applicants
4. **Check Status**: Verify the certificate status is appropriate before notifying applicants
5. **Track Messages**: Monitor the WhatsApp messages table to ensure delivery

## Support

For issues or questions about this feature, please contact the development team or refer to the main application documentation.

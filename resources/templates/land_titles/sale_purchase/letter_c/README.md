# Letter C Templates - Quick Reference

This directory contains 8 templates for sale and purchase documents based on Letter C land titles.

## Template Files

| File Name | Seller Type | Seller Count | Buyer Count | Use Case |
|-----------|-------------|--------------|-------------|----------|
| `single_seller_single_buyer.docx` | Regular | 1 | 1 | Most common: One person selling to one person |
| `single_seller_multiple_buyers.docx` | Regular | 1 | 2+ | One seller to multiple buyers (e.g., family buying together) |
| `multiple_sellers_single_buyer.docx` | Regular | 2+ | 1 | Multiple sellers to one buyer (e.g., co-owners selling) |
| `multiple_sellers_multiple_buyers.docx` | Regular | 2+ | 2+ | Multiple sellers to multiple buyers |
| `single_seller_with_consent_single_buyer.docx` | With Consent | 1 + consent | 1 | Seller with spouse/consent to one buyer |
| `single_seller_with_consent_multiple_buyers.docx` | With Consent | 1 + consent | 2+ | Seller with spouse/consent to multiple buyers |
| `heir_sellers_single_buyer.docx` | Heir | 1+ heirs | 1 | Heir(s) selling inherited property to one buyer |
| `heir_sellers_multiple_buyers.docx` | Heir | 1+ heirs | 2+ | Heir(s) selling inherited property to multiple buyers |

## When Each Template is Used

### Regular Seller Templates
Used when:
- `is_heir` flag is NOT checked
- No consent applicant exists
- Regular sale transaction

**single_seller_single_buyer.docx**
- 1 seller applicant (type: Seller, code: `seller`)
- 1 buyer applicant (type: Buyer, code: `buyer`)

**single_seller_multiple_buyers.docx**
- 1 seller applicant
- 2+ buyer applicants

**multiple_sellers_single_buyer.docx**
- 2+ seller applicants
- 1 buyer applicant

**multiple_sellers_multiple_buyers.docx**
- 2+ seller applicants
- 2+ buyer applicants

### With Consent Templates
Used when:
- `is_heir` flag is NOT checked
- At least one consent applicant exists (type: Consent, code: `consent`)
- Requires spousal or other consent

**single_seller_with_consent_single_buyer.docx**
- 1 seller applicant
- 1 consent applicant
- 1 buyer applicant

**single_seller_with_consent_multiple_buyers.docx**
- 1 seller applicant
- 1 consent applicant
- 2+ buyer applicants

### Heir Templates
Used when:
- `is_heir` flag IS checked in the land title
- Selling inherited property

**heir_sellers_single_buyer.docx**
- 1+ seller applicants (heirs)
- 1 buyer applicant

**heir_sellers_multiple_buyers.docx**
- 1+ seller applicants (heirs)
- 2+ buyer applicants

## Template Placeholders

### Common to All Templates

**Document Information:**
- `${document_number}` - Auto-generated document number
- `${document_day}` - Day name (e.g., "Senin")
- `${document_date}` - Day of month (e.g., "23")
- `${document_month}` - Month name (e.g., "November")
- `${document_year}` - Year (e.g., "2025")

**PPAT Information:**
- `${ppat_name}` - PPAT/Notary name
- `${ppat_address}` - PPAT full address

**Letter C Information:**
- `${letter_c_name}` - Name on Letter C
- `${letter_c_number}` - Letter C number (Nomor C)
- `${letter_c_persil}` - Persil number
- `${letter_c_class}` - Class
- `${letter_c_land_area}` - Land area from Letter C
- `${letter_c_date}` - Letter C date

**Land Details:**
- `${land_area}` - Land area in m²
- `${land_area_words}` - Land area in Indonesian words + "Meter Persegi"
- `${north_border}` - North border description
- `${east_border}` - East border description
- `${south_border}` - South border description
- `${west_border}` - West border description

**Transaction:**
- `${transaction_amount}` - Amount (formatted: 1.000.000)
- `${transaction_amount_words}` - Amount in words
- `${pph}` - Income Tax (PPh)
- `${bphtb}` - Land and Building Rights Acquisition Tax
- `${adm}` - Administration fee
- `${pbb}` - Property Tax
- `${adm_certificate}` - Certificate administration fee
- `${total_amount}` - Total amount

### Single Seller Templates
- `${seller_name}`, `${seller_birthplace}`, `${seller_birthdate}`
- `${seller_age}`, `${seller_occupation}`, `${seller_national_id_number}`, `${seller_address}`

### Multiple Sellers Templates
Use row cloning in a table with:
- `${seller_name#1}`, `${seller_name#2}`, etc.
- `${seller_birthplace#1}`, `${seller_birthplace#2}`, etc.
- Similar pattern for all seller fields

### With Consent Templates
Include both seller placeholders AND:
- `${consent_name}`, `${consent_birthplace}`, `${consent_birthdate}`
- `${consent_age}`, `${consent_occupation}`, `${consent_national_id_number}`, `${consent_address}`

### Single Buyer Templates
- `${buyer_name}`, `${buyer_birthplace}`, `${buyer_birthdate}`
- `${buyer_age}`, `${buyer_occupation}`, `${buyer_national_id_number}`, `${buyer_address}`

### Multiple Buyers Templates
Use row cloning in a table with:
- `${buyer_name#1}`, `${buyer_name#2}`, etc.
- `${buyer_birthplace#1}`, `${buyer_birthplace#2}`, etc.
- Similar pattern for all buyer fields

### Witnesses
Either numbered (for fixed count):
- `${witness_1_name}`, `${witness_1_address}`, etc.
- `${witness_2_name}`, `${witness_2_address}`, etc.

Or use row cloning:
- `${witness_name#1}`, `${witness_name#2}`, etc.

## Editing Guidelines

1. **Maintain Legal Accuracy**: Each template type may have different legal language requirements
2. **Test Placeholders**: Ensure all placeholders work correctly after editing
3. **Preserve Formatting**: Keep professional formatting (margins, fonts, spacing)
4. **Use Tables for Multiple Records**: Tables with row cloning work best for sellers/buyers
5. **Include All Required Sections**: Parties, land description, transaction details, signatures

## Testing

After editing a template:

1. Create a test land title with:
   - Letter C land title selected
   - Appropriate is_heir flag
   - Correct applicants (seller, buyer, consent if needed)
   - All required data filled

2. Generate document and verify:
   - All placeholders replaced correctly
   - Formatting is preserved
   - Multiple sellers/buyers appear correctly
   - Legal language is appropriate

## Need More Help?

See the main documentation:
- `resources/templates/land_titles/README.md` - General template guide
- `resources/templates/land_titles/TEMPLATE_PLACEHOLDERS.md` - Complete placeholder list
- `resources/templates/land_titles/sale_purchase/README.md` - Sale purchase specific docs

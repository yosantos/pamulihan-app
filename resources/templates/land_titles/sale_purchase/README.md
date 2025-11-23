# Sale Purchase (Jual Beli) Templates

This directory contains Word document templates for sale and purchase land title documents.

## Directory Structure

```
sale_purchase/
├── README.md (this file)
├── letter_c/ (Letter C based templates)
│   ├── single_seller_single_buyer.docx
│   ├── single_seller_multiple_buyers.docx
│   ├── multiple_sellers_single_buyer.docx
│   ├── multiple_sellers_multiple_buyers.docx
│   ├── single_seller_with_consent_single_buyer.docx
│   ├── single_seller_with_consent_multiple_buyers.docx
│   ├── heir_sellers_single_buyer.docx
│   └── heir_sellers_multiple_buyers.docx
└── certificate/ (Future: Certificate/BPN based templates)
```

## Template Selection Logic

The system automatically selects the appropriate template based on:

### 1. Land Source Detection
- **Letter C**: If the land title has `letter_c_land_title_id` set
- **Certificate**: If the land title does NOT have Letter C (uses BPN certificate)

### 2. Seller Type Detection
The system checks in this order:

1. **Heir Sellers** (`heir_sellers`)
   - Detected when `is_heir` field is checked in the land title
   - Can have one or more heir sellers

2. **Single Seller with Consent** (`single_seller_with_consent`)
   - Detected when there's an applicant with type "Consent" (code: `consent`)
   - Typically for cases requiring spousal consent

3. **Regular Sellers** (`single_seller` or `multiple_sellers`)
   - Default case
   - Determined by counting sellers with applicant type "Seller" (code: `seller`)

### 3. Buyer Count Detection
- **Single Buyer** (`single_buyer`): One applicant with type "Buyer" (code: `buyer`)
- **Multiple Buyers** (`multiple_buyers`): More than one buyer applicant

## Letter C Templates

### Current Available Templates:

1. **single_seller_single_buyer.docx**
   - One regular seller, one buyer
   - Land source: Letter C

2. **single_seller_multiple_buyers.docx**
   - One regular seller, multiple buyers
   - Land source: Letter C

3. **multiple_sellers_single_buyer.docx**
   - Multiple regular sellers, one buyer
   - Land source: Letter C

4. **multiple_sellers_multiple_buyers.docx**
   - Multiple regular sellers, multiple buyers
   - Land source: Letter C

5. **single_seller_with_consent_single_buyer.docx**
   - One seller with consent person, one buyer
   - Land source: Letter C
   - Requires consent applicant

6. **single_seller_with_consent_multiple_buyers.docx**
   - One seller with consent person, multiple buyers
   - Land source: Letter C
   - Requires consent applicant

7. **heir_sellers_single_buyer.docx**
   - Heir seller(s), one buyer
   - Land source: Letter C
   - Requires `is_heir` flag checked

8. **heir_sellers_multiple_buyers.docx**
   - Heir seller(s), multiple buyers
   - Land source: Letter C
   - Requires `is_heir` flag checked

## Editing Templates

Each template is currently a copy of the base template. You should edit each one individually to meet the specific legal requirements for that scenario.

### Placeholders Available:

See the main `TEMPLATE_PLACEHOLDERS.md` for the complete list. Key placeholders include:

**Document Info:**
- `${document_number}`, `${document_date}`, `${document_day}`, `${document_month}`, `${document_year}`

**PPAT:**
- `${ppat_name}`, `${ppat_address}`

**Seller(s):**
- Single: `${seller_name}`, `${seller_address}`, etc.
- Multiple: Use row cloning with `${seller_name#1}`, `${seller_name#2}`, etc.

**Consent Person** (for templates with consent):
- `${consent_name}`, `${consent_birthplace}`, `${consent_birthdate}`
- `${consent_age}`, `${consent_occupation}`, `${consent_national_id_number}`, `${consent_address}`

**Buyer(s):**
- Single: `${buyer_name}`, `${buyer_address}`, etc.
- Multiple: Use row cloning with `${buyer_name#1}`, `${buyer_name#2}`, etc.

**Land Information:**
- Letter C: `${letter_c_name}`, `${letter_c_number}`, `${letter_c_persil}`, `${letter_c_class}`, `${letter_c_land_area}`, `${letter_c_date}`
- Borders: `${north_border}`, `${east_border}`, `${south_border}`, `${west_border}`
- Area: `${land_area}`, `${land_area_words}`

**Transaction:**
- `${transaction_amount}`, `${transaction_amount_words}`
- Fees: `${pph}`, `${bphtb}`, `${adm}`, `${pbb}`, `${adm_certificate}`
- `${total_amount}`

**Witnesses:**
- Numbered: `${witness_1_name}`, `${witness_2_name}`, etc.
- Or use row cloning: `${witness_name#1}`, `${witness_name#2}`, etc.

## Testing Templates

After editing a template:

1. Create a land title with the appropriate configuration:
   - Set `letter_c_land_title_id` for Letter C
   - Add sellers with correct applicant type
   - Add consent applicant if needed (for "with_consent" templates)
   - Check `is_heir` flag for heir templates
   - Add buyer(s)

2. Click "Generate Document" action

3. Review the generated document for:
   - All placeholders are replaced
   - Formatting is correct
   - Multiple records (sellers/buyers) are displayed properly
   - Legal text is accurate for that scenario

## Future: Certificate Templates

The `certificate/` directory will contain templates for land titles based on BPN certificates instead of Letter C. These will follow the same naming convention but will be used when `letter_c_land_title_id` is null.

## Notes

- All templates must be in `.docx` format (not .doc)
- Use PHPWord-compatible placeholders: `${variable_name}`
- For multiple records, use row cloning in tables
- Test with real data before deploying to production
- Each scenario may have different legal requirements - ensure templates comply with PPAT standards

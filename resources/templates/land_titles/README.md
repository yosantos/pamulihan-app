# Land Title Document Templates

This directory contains Word document templates for generating land title documents.

**Location:** `resources/templates/land_titles/`

Templates are stored in `resources` (not `storage`) so they can be version controlled with Git.

## Directory Structure

```
land_titles/
├── README.md (this file)
├── TEMPLATE_PLACEHOLDERS.md (complete list of available placeholders)
├── sale_purchase/ (Jual Beli templates)
│   ├── README.md (Sale Purchase specific documentation)
│   ├── letter_c/ (Letter C based templates)
│   └── certificate/ (Certificate/BPN based templates - future)
├── grant/ (Hibah templates)
├── inheritance/ (Waris templates)
└── exchange/ (Tukar Menukar templates)
```

## Template Naming Convention

Templates are organized by land source and follow this naming pattern:

### For Sale Purchase (Jual Beli):
```
{land_source}/{seller_config}_{buyer_config}.docx
```

#### Land Source:
- `letter_c/` - Land title based on Letter C document
- `certificate/` - Land title based on Certificate from BPN

#### Seller Configuration:
- `single_seller` - One seller (regular)
- `multiple_sellers` - Multiple sellers
- `single_seller_with_consent` - Single seller with consent person
- `heir_sellers` - Heir seller(s)

#### Buyer Configuration:
- `single_buyer` - One buyer
- `multiple_buyers` - Multiple buyers

### Examples:

```
sale_purchase/letter_c/multiple_sellers_single_buyer.docx
sale_purchase/letter_c/single_seller_with_consent_multiple_buyers.docx
sale_purchase/letter_c/heir_sellers_single_buyer.docx
```

### For Other Types (Grant, Inheritance, Exchange):
```
{type_code}_{seller_config}_{buyer_config}.docx
```

Examples:
```
grant_single_seller_single_buyer.docx
inheritance_multiple_sellers_single_buyer.docx
```

## Creating a New Template

### Step 1: Create the Word Document

1. Open Microsoft Word or LibreOffice Writer
2. Design your document layout
3. Add all necessary text, formatting, headers, footers, etc.

### Step 2: Add Placeholders

Insert placeholders where dynamic data should appear. Use this format:
```
${placeholder_name}
```

Example:
```
Nama: ${seller_name}
Tempat Lahir: ${seller_birthplace}
Tanggal Lahir: ${seller_birthdate}
```

### Step 3: Handle Multiple Records (Sellers/Witnesses)

For multiple sellers or witnesses, you can use two approaches:

**Approach 1: Row Cloning (Recommended)**

Create a table and use a single placeholder in the first row:
```
| No | Nama          | Alamat          |
|----|---------------|-----------------|
| 1  | ${seller_name}| ${seller_address}|
```

The system will automatically clone this row for each seller.

**Approach 2: Numbered Placeholders**

Use numbered placeholders for a fixed number of records:
```
Penjual 1: ${seller_1_name}
Penjual 2: ${seller_2_name}
```

### Step 4: Common Placeholders

See `TEMPLATE_PLACEHOLDERS.md` for the complete list. Common ones include:

**Document Info:**
- `${document_number}` - Document number
- `${document_date}` - Date
- `${document_day}` - Day name (Indonesian)
- `${document_month}` - Month name (Indonesian)
- `${document_year}` - Year

**Parties:**
- `${seller_name}`, `${buyer_name}` - Names
- `${seller_address}`, `${buyer_address}` - Addresses
- `${seller_national_id_number}`, `${buyer_national_id_number}` - NIK

**Land Info:**
- `${sppt_number}`, `${sppt_year}` - SPPT information
- `${land_area}`, `${land_area_words}` - Land area
- `${north_border}`, `${east_border}`, `${south_border}`, `${west_border}` - Borders

**Transaction:**
- `${transaction_amount}`, `${transaction_amount_words}` - Transaction amount
- `${total_amount}` - Total amount including fees

### Step 5: Save the Template

1. Save the document as `.docx` format (not .doc)
2. Use the correct naming convention
3. Place in the appropriate directory:
   - Sale Purchase → `sale_purchase/`
   - Grant → `grant/`
   - Inheritance → `inheritance/`
   - Exchange → `exchange/`

## Template Selection System

The system automatically selects the appropriate template based on:

**For Sale Purchase:**
1. Land source (Letter C vs Certificate from BPN)
2. Seller type (Regular, With Consent, or Heir)
3. Seller count (Single or Multiple)
4. Buyer count (Single or Multiple)

**For Other Types (Grant, Inheritance, Exchange):**
1. Seller count (Single or Multiple)
2. Buyer count (Single or Multiple)

The system expects exact template matches - there is no fallback mechanism. Ensure all required templates are created for your use cases.

## Testing Your Template

After creating a template:

1. Create or edit a land title in the system
2. Add the appropriate sellers, buyers, and other data
3. Click the "Generate Document" action
4. Review the generated document

## Tips for Best Results

1. **Use Tables for Structure**: Tables help maintain alignment and are easier to work with
2. **Test with Real Data**: Generate documents with actual data to verify formatting
3. **Consider Page Breaks**: Add page breaks where needed for multi-page documents
4. **Use Consistent Formatting**: Apply consistent fonts, sizes, and spacing
5. **Include Headers/Footers**: Add page numbers, watermarks if needed
6. **Check Placeholder Spelling**: Typos in placeholders will show as-is in the document

## Common Issues

### Placeholder Not Replaced

**Problem**: Placeholder appears as `${seller_name}` in the output
**Solution**:
- Check spelling matches exactly with TEMPLATE_PLACEHOLDERS.md
- Ensure you're using `${...}` format, not `{...}` or `[...]`
- Make sure the placeholder is plain text, not in a text box or shape

### Row Cloning Not Working

**Problem**: Only one seller shown when using row cloning
**Solution**:
- Ensure the placeholder is in a table cell
- Use the base placeholder name without `#1` suffix
- Check that the table row is properly formatted

### Formatting Lost

**Problem**: Generated document loses formatting
**Solution**:
- Use Word's built-in styles instead of direct formatting
- Avoid complex layouts that don't translate well
- Test with simpler formatting first

## Need Help?

See the complete placeholder reference in `TEMPLATE_PLACEHOLDERS.md`
Review existing templates in the `sale_purchase/` directory for examples

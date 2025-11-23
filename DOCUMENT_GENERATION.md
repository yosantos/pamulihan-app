# Land Title Document Generation System

## Overview

The Pamulihan App includes an automated document generation system for land title documents. This system uses Word document templates with placeholders that are automatically replaced with actual data from the database.

## Features

- **Template-Based Generation**: Use Word `.docx` files as templates
- **Dynamic Placeholders**: Automatically replace placeholders with real data
- **Multiple Template Support**: Different templates for different scenarios
- **Intelligent Fallback**: Automatic fallback to default template if specific one not found
- **Multi-Party Support**: Handle multiple sellers, buyers, and witnesses
- **Indonesian Formatting**: Dates, numbers, and currency in Indonesian format
- **Auto-Generated Wordings**: Numbers automatically converted to Indonesian words

## How It Works

1. **User Creates Land Title**: Admin creates a land title record with all necessary data
2. **User Clicks Generate**: Clicks "Generate Document" button in the admin panel
3. **System Selects Template**: Automatically selects the appropriate template based on:
   - Land title type (sale/purchase, grant, inheritance, exchange)
   - Number of sellers (single/multiple)
   - Number of buyers (single/multiple)
4. **System Fills Data**: Replaces all placeholders with actual data
5. **Document Downloaded**: Generated Word document is downloaded automatically

## System Components

### 1. Document Service
**Location**: `app/Services/LandTitleDocumentService.php`

Handles all document generation logic:
- Template selection
- Data population
- Format conversion
- File generation

### 2. Templates Storage
**Location**: `storage/app/templates/land_titles/`

Organized by land title type:
```
land_titles/
├── sale_purchase/    (Jual Beli)
├── grant/           (Hibah)
├── inheritance/     (Waris)
└── exchange/        (Tukar Menukar)
```

### 3. Generated Documents
**Location**: `storage/app/generated_documents/`

All generated documents are temporarily stored here before download.

### 4. Filament Resource Action
**Location**: `app/Filament/Resources/LandTitleResource.php` (lines 346-373)

Provides the "Generate Document" button in the admin interface.

## Template Naming Convention

Templates use this naming pattern:
```
{type_code}_{seller_config}_{buyer_config}.docx
```

**Examples**:
- `sale_purchase_single_seller_single_buyer.docx`
- `sale_purchase_multiple_sellers_single_buyer.docx`
- `grant_single_seller_single_buyer.docx`
- `inheritance_multiple_sellers_multiple_buyers.docx`

## Available Placeholders

See `storage/app/templates/land_titles/TEMPLATE_PLACEHOLDERS.md` for the complete list.

**Categories**:
- PPAT (Notary) Information
- Document Information (number, date, etc.)
- Seller Information
- Buyer Information
- Land Information (SPPT, Letter C)
- Land Area and Borders
- Transaction Information (amounts, fees, taxes)
- Witness Information

**Format**: All placeholders use `${placeholder_name}` format

**Example**:
```
Nama: ${seller_name}
Alamat: ${seller_address}
NIK: ${seller_national_id_number}
```

## Data Sources

The system pulls data from:

1. **Land Title Record**: Transaction details, land area, borders
2. **SPPT Land Title**: SPPT number, owner, block, area
3. **Letter C Land Title**: Letter C details
4. **Applicants**: Sellers, buyers, witnesses (linked to Users)
5. **Users**: Personal information (name, address, NIK, etc.)
6. **Creator**: PPAT/Notary who created the record
7. **Auto-Generated**: Number/year, wordings, calculated totals

## How to Use

### For Administrators

1. **Navigate to Land Titles**: Go to Land Management → Land Titles
2. **Select a Record**: Choose the land title you want to generate a document for
3. **Click Generate Document**: Click the download icon button
4. **Confirm**: Confirm the action in the modal
5. **Download**: Document will be generated and downloaded automatically

### For Template Creators

1. **Read the Guide**: See `storage/app/templates/land_titles/README.md`
2. **Review Placeholders**: Check `TEMPLATE_PLACEHOLDERS.md` for available variables
3. **Create Word Document**: Design your template in Word
4. **Add Placeholders**: Insert `${placeholder_name}` where data should appear
5. **Save Template**: Save as `.docx` with correct naming convention
6. **Place in Directory**: Put in appropriate subdirectory (sale_purchase, grant, etc.)
7. **Test**: Generate a document to verify it works

## Creating Your First Template

**Quick Start Example** (Sale/Purchase with Single Seller and Buyer):

1. Open Microsoft Word
2. Create a basic structure:

```
AKTA JUAL BELI
No: ${document_number}

Pada hari ${document_day}, tanggal ${document_date} bulan ${document_month}
tahun ${document_year}

PENJUAL:
Nama: ${seller_name}
Alamat: ${seller_address}
NIK: ${seller_national_id_number}

PEMBELI:
Nama: ${buyer_name}
Alamat: ${buyer_address}
NIK: ${buyer_national_id_number}

OBJEK JUAL BELI:
Luas Tanah: ${land_area} m² (${land_area_words})
Batas Utara: ${north_border}
Batas Timur: ${east_border}
Batas Selatan: ${south_border}
Batas Barat: ${west_border}

HARGA JUAL BELI: Rp ${transaction_amount}
Terbilang: ${transaction_amount_words}
```

3. Save as: `sale_purchase_single_seller_single_buyer.docx`
4. Place in: `storage/app/templates/land_titles/sale_purchase/`
5. Test by generating a document

## Supported Data Types

- **Text**: Names, addresses, descriptions
- **Numbers**: Transaction amounts, land area, fees
- **Dates**: Birthdates, document dates (Indonesian format)
- **Currency**: All monetary values (Indonesian format: 1.000.000)
- **Wordings**: Auto-converted number-to-words in Indonesian

## Auto-Generated Fields

These fields are automatically generated/calculated:

1. **Land Title Number & Year**: Auto-incremented yearly
2. **Transaction Amount Wording**: Indonesian words
3. **Land Area Wording**: Indonesian words
4. **Total Amount**: Sum of transaction + all fees/taxes
5. **Ages**: Calculated from birthdates
6. **Formatted Dates**: Converted to Indonesian format

## Multi-Party Handling

### Multiple Sellers

The system can handle multiple sellers in two ways:

**1. Row Cloning (Recommended)**

Create a table row with base placeholders:
```
| No | Nama          | Alamat          |
|----|---------------|-----------------|
| 1  | ${seller_name}| ${seller_address}|
```

System automatically clones for each seller.

**2. Numbered Placeholders**

Use numbered variants:
```
Penjual 1: ${seller_1_name}
Penjual 2: ${seller_2_name}
```

### Multiple Buyers

Same approach as sellers, using `${buyer_*}` placeholders.

### Multiple Witnesses

Use `${witness_1_name}`, `${witness_2_name}`, etc., or table cloning with `${witness_name}`.

## Template Fallback System

If a specific template is not found, the system falls back to:
```
{type_code}_multiple_sellers_single_buyer.docx
```

**Example**: If `sale_purchase_single_seller_single_buyer.docx` doesn't exist, it will use `sale_purchase_multiple_sellers_single_buyer.docx`

This means you need at least one template per land title type.

## Troubleshooting

### Placeholder Not Replaced

**Problem**: Placeholder shows as `${seller_name}` in output

**Solutions**:
- Check spelling matches `TEMPLATE_PLACEHOLDERS.md` exactly
- Ensure using `${...}` format, not `{...}` or `[...]`
- Verify placeholder is plain text, not in text box/shape
- Check the data exists in the database

### Template Not Found Error

**Problem**: "Template not found" error

**Solutions**:
- Verify template file exists in correct directory
- Check file naming matches convention exactly
- Ensure `.docx` extension (not `.doc`)
- Check file permissions (must be readable)
- Verify at least the fallback template exists

### Missing Data Shows as "-"

**Problem**: Fields showing as "-" instead of data

**Explanation**: This is expected behavior when data is not available

**Solutions**:
- Check the land title record has all necessary data filled
- Verify related records (SPPT, Letter C) are linked
- Ensure applicants are added with correct types
- Check user records have complete information

### Generated Document has Formatting Issues

**Solutions**:
- Use Word's built-in styles, not direct formatting
- Keep layout simple and avoid complex structures
- Test with actual data to verify
- Use tables for alignment instead of tabs/spaces

## File Structure Summary

```
App Structure:
├── app/
│   ├── Services/
│   │   └── LandTitleDocumentService.php    (generation logic)
│   ├── Models/
│   │   ├── LandTitle.php                   (main model)
│   │   ├── LandTitleApplicant.php         (sellers/buyers)
│   │   └── ...
│   ├── Observers/
│   │   └── LandTitleObserver.php          (auto-calculations)
│   └── Filament/Resources/
│       └── LandTitleResource.php          (admin UI + action)
│
├── storage/app/
│   ├── templates/land_titles/             (Word templates)
│   │   ├── README.md                      (main guide)
│   │   ├── TEMPLATE_PLACEHOLDERS.md       (placeholder reference)
│   │   ├── sale_purchase/
│   │   ├── grant/
│   │   ├── inheritance/
│   │   └── exchange/
│   │
│   └── generated_documents/               (output files)
│
└── vendor/phpoffice/phpword/              (PHPWord library)
```

## Technologies Used

- **PHPWord**: Microsoft Office Word document processing
- **Laravel**: Framework
- **Filament**: Admin panel
- **TemplateProcessor**: PHPWord's template engine

## Requirements

- PHP 8.2+
- PHPWord package (already installed via Composer)
- Write permissions on `storage/app/generated_documents/`
- Read permissions on `storage/app/templates/`

## Performance Notes

- Template loading and processing is fast (< 1 second typically)
- Generated files are deleted after download (no storage accumulation)
- Supports documents up to several hundred pages
- No limit on number of applicants/witnesses

## Security Considerations

- Generated files contain sensitive personal information
- Files are deleted immediately after download
- Access controlled through Filament permissions
- Only authenticated admins can generate documents
- Template files should not be web-accessible

## Future Enhancements

Potential improvements:
- PDF export option
- Email generated documents
- Batch generation
- Custom template uploads via admin panel
- Template versioning
- Digital signatures integration
- Document archiving option
- Template preview before generation

## Support & Documentation

- **Template Guide**: `storage/app/templates/land_titles/README.md`
- **Placeholder Reference**: `storage/app/templates/land_titles/TEMPLATE_PLACEHOLDERS.md`
- **Type-Specific Guides**: README.md in each type subdirectory
- **Service Code**: `app/Services/LandTitleDocumentService.php`
- **Resource Code**: `app/Filament/Resources/LandTitleResource.php`

## Getting Help

If you encounter issues:
1. Check the troubleshooting section above
2. Review the template guides
3. Examine existing templates in `sale_purchase/` directory
4. Check application logs for detailed error messages
5. Verify all required data is present in the database

## License

This document generation system is part of the Pamulihan App project.

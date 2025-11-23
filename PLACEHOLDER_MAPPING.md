# Land Title Document Placeholder Mapping

This document provides a comprehensive mapping between database fields and document template placeholders used in the Land Title document generation system.

## Placeholder Format

All placeholders in the Word document templates use the format: `${variable_name}`

## Document Information

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${document_number}` | `LandTitle.formatted_number` | Full document number (number/year) | "373/2025" |
| `${document_day}` | `LandTitle.created_at` | Day name in Indonesian | "Senin" |
| `${document_date}` | `LandTitle.created_at` | Day number | "15" |
| `${document_month}` | `LandTitle.created_at` | Month name in Indonesian | "November" |
| `${document_year}` | `LandTitle.created_at` | Year number | "2025" |
| `${document_year_words}` | `LandTitle.created_at` | Year in Indonesian words | "dua ribu dua puluh lima" |

## PPAT Information (Notary)

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${ppat_name}` | `LandTitle.creator.name` | Name of PPAT who created the document | "Dr. Ahmad Suryanto, S.H., M.Kn" |
| `${ppat_address}` | `LandTitle.creator` (formatted) | Full address of PPAT | "RT 01 / RW 02, Kel. Sumber, Kec. Batu, Kota Batu, Jawa Timur" |

## Seller Information (Multiple Sellers Supported)

### For templates with row cloning:
| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${seller_name#1}` | `LandTitleApplicant.user.name` | Seller name (row 1) | "Budi Santoso" |
| `${seller_birthplace#1}` | `LandTitleApplicant.user.birthplace` | Place of birth (row 1) | "Malang" |
| `${seller_birthdate#1}` | `LandTitleApplicant.user.birthdate` | Formatted birthdate (row 1) | "15 Januari 1980" |
| `${seller_age#1}` | Calculated from birthdate | Age in years (row 1) | "45 tahun" |
| `${seller_occupation#1}` | `LandTitleApplicant.user.occupation` | Occupation (row 1) | "Wiraswasta" |
| `${seller_national_id_number#1}` | `LandTitleApplicant.user.national_id_number` | NIK (row 1) | "3573012345678901" |
| `${seller_address#1}` | `LandTitleApplicant.user` (formatted) | Full address (row 1) | "RT 03 / RW 04, Kel. Oro-oro Dowo, Kec. Klojen, Kota Malang, Jawa Timur" |

*Note: Row numbers increment for each seller (#1, #2, #3, etc.)*

### For templates without row cloning (single values):
Use the same placeholders without row numbers: `${seller_name}`, `${seller_birthplace}`, etc.

## Buyer Information (Single Buyer)

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${buyer_name}` | `LandTitleApplicant.user.name` | Buyer name | "Siti Rahayu" |
| `${buyer_birthplace}` | `LandTitleApplicant.user.birthplace` | Place of birth | "Surabaya" |
| `${buyer_birthdate}` | `LandTitleApplicant.user.birthdate` | Formatted birthdate | "20 Maret 1985" |
| `${buyer_age}` | Calculated from birthdate | Age in years | "40 tahun" |
| `${buyer_occupation}` | `LandTitleApplicant.user.occupation` | Occupation | "Guru" |
| `${buyer_national_id_number}` | `LandTitleApplicant.user.national_id_number` | NIK | "3578012345678902" |
| `${buyer_address}` | `LandTitleApplicant.user` (formatted) | Full address | "RT 02 / RW 05, Kel. Gubeng, Kec. Gubeng, Kota Surabaya, Jawa Timur" |

## SPPT Land Title Information

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${sppt_number}` | `SpptLandTitle.number` | SPPT number | "33.73.010.001.001-0001.0" |
| `${sppt_year}` | `SpptLandTitle.year` | SPPT year | "2024" |
| `${sppt_owner}` | `SpptLandTitle.owner` | Owner name on SPPT | "Budi Santoso" |
| `${sppt_block}` | `SpptLandTitle.block` | Block number | "001" |
| `${sppt_land_area}` | `SpptLandTitle.land_area` | Land area in m² | "150.00" |
| `${sppt_building_area}` | `SpptLandTitle.building_area` | Building area in m² | "72.00" |
| `${sppt_village}` | `SpptLandTitle.village.name` | Village name | "Sumber" |

## Letter C Land Title Information

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${letter_c_name}` | `LetterCLandTitle.name` | Owner name on Letter C | "Budi Santoso" |
| `${letter_c_number}` | `LetterCLandTitle.number_of_c` | Letter C number | "C-123" |
| `${letter_c_persil}` | `LetterCLandTitle.number_of_persil` | Persil number | "45" |
| `${letter_c_class}` | `LetterCLandTitle.class` | Land class | "II" |
| `${letter_c_land_area}` | `LetterCLandTitle.land_area` | Land area in m² | "150.00" |
| `${letter_c_date}` | `LetterCLandTitle.date` | Date of Letter C | "15 Januari 2020" |

## Land Information

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${land_area}` | `LandTitle.area_of_the_land` | Land area (numeric) | "150.00" |
| `${land_area_words}` | `LandTitle.area_of_the_land_wording` or calculated | Land area in words | "seratus lima puluh" |
| `${north_border}` | `LandTitle.north_border` | Northern boundary description | "Jalan Raya Sumber" |
| `${east_border}` | `LandTitle.east_border` | Eastern boundary description | "Tanah milik Pak Joko" |
| `${south_border}` | `LandTitle.south_border` | Southern boundary description | "Sungai kecil" |
| `${west_border}` | `LandTitle.west_border` | Western boundary description | "Tanah milik Bu Ani" |

## Transaction Information

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${transaction_amount}` | `LandTitle.transaction_amount` | Transaction amount (formatted) | "450.000.000" |
| `${transaction_amount_words}` | `LandTitle.transaction_amount_wording` or calculated | Amount in words | "empat ratus lima puluh juta rupiah" |

## Fees and Taxes

| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${pph}` | `LandTitle.pph` | Income tax (PPh) | "11.250.000" |
| `${bphtb}` | `LandTitle.bphtb` | Transfer tax (BPHTB) | "18.000.000" |
| `${adm}` | `LandTitle.adm` | Administration fee | "500.000" |
| `${pbb}` | `LandTitle.pbb` | Property tax (PBB) | "750.000" |
| `${adm_certificate}` | `LandTitle.adm_certificate` | Certificate administration fee | "1.000.000" |
| `${total_amount}` | `LandTitle.total_amount` | Total amount (all fees included) | "481.500.000" |

## Witnesses Information (Multiple Witnesses Supported)

### For templates with row cloning:
| Placeholder | Source | Description | Example |
|------------|--------|-------------|---------|
| `${witness_name#1}` | `LandTitleApplicant.user.name` | Witness name (row 1) | "Ahmad Hidayat" |
| `${witness_birthplace#1}` | `LandTitleApplicant.user.birthplace` | Place of birth (row 1) | "Batu" |
| `${witness_birthdate#1}` | `LandTitleApplicant.user.birthdate` | Formatted birthdate (row 1) | "10 Februari 1975" |
| `${witness_age#1}` | Calculated from birthdate | Age in years (row 1) | "50 tahun" |
| `${witness_occupation#1}` | `LandTitleApplicant.user.occupation` | Occupation (row 1) | "Petani" |
| `${witness_national_id_number#1}` | `LandTitleApplicant.user.national_id_number` | NIK (row 1) | "3573011234567890" |
| `${witness_address#1}` | `LandTitleApplicant.user` (formatted) | Full address (row 1) | "RT 01 / RW 03, Kel. Sidomulyo, Kec. Batu, Kota Batu, Jawa Timur" |

*Note: Row numbers increment for each witness (#1, #2, #3, etc.)*

### For templates without row cloning (numbered witnesses):
Use numbered placeholders: `${witness_1_name}`, `${witness_2_name}`, etc.

## Address Formatting

Addresses are automatically formatted from User model fields in this order:
- RT/RW (if available)
- Village
- District
- City
- Province

Example: `"RT 01 / RW 02, Kel. Sumber, Kec. Batu, Kota Batu, Jawa Timur"`

## Date Formatting

Dates are formatted in Indonesian format: `{day} {month} {year}`
- Day: numeric (1-31)
- Month: Indonesian month name (Januari, Februari, etc.)
- Year: 4-digit year

Example: `"15 November 2025"`

## Number to Words Conversion

The service includes Indonesian number-to-words conversion for:
- Transaction amounts
- Land areas
- Years

Examples:
- 150 → "seratus lima puluh"
- 2025 → "dua ribu dua puluh lima"
- 450000000 → "empat ratus lima puluh juta"

## Template Naming Convention

Templates are stored in: `storage/app/templates/land_titles/{land_title_type_code}/`

Template naming format: `{code}_{seller_config}_{buyer_config}.docx`

Examples:
- `sale_purchase_single_seller_single_buyer.docx`
- `sale_purchase_multiple_sellers_single_buyer.docx`
- `sale_purchase_single_seller_multiple_buyers.docx`
- `sale_purchase_multiple_sellers_multiple_buyers.docx`

## Generated Document Naming

Generated documents are saved temporarily in: `storage/app/generated_documents/`

Filename format: `{type_code}_{document_number}_{timestamp}.docx`

Example: `sale_purchase_373_2025_20251122103045.docx`

## Important Notes

1. All placeholders use the format `${variable}` - ensure your Word template uses this exact format
2. For multiple sellers/witnesses, the service attempts to clone rows first. If cloning fails, it falls back to single values
3. Missing data is replaced with "-" to prevent errors
4. Dates are automatically converted to Indonesian format
5. Numbers are formatted with Indonesian thousand separators (e.g., 450.000.000)
6. Generated files are automatically deleted after download
7. The service gracefully handles missing relationships (SPPT, Letter C)

## Converting Existing Templates

To convert your existing Word templates to use these placeholders:

1. Open the template in Microsoft Word
2. Use Find & Replace to change existing placeholders to `${variable}` format
3. For repeating sections (sellers, witnesses), ensure placeholders are in a table row
4. Save as .docx format
5. Place in the appropriate directory: `storage/app/templates/land_titles/{code}/`
6. Name according to the convention above

## Testing the System

1. Ensure you have a LandTitle record with:
   - At least one seller (applicant type: "Seller")
   - One buyer (applicant type: "Buyer")
   - Optional: witnesses (applicant type: "Witness")
   - Optional: SPPT and/or Letter C reference

2. Click "Generate Document" button in Filament
3. Check that all data is properly populated
4. Verify dates are in Indonesian format
5. Confirm numbers are formatted correctly

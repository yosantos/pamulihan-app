# Land Title Document Template Placeholders

This document lists all available placeholders for land title document templates.

## Template Structure

### For Sale Purchase (Jual Beli):
Templates are organized by land source: `{land_source}/{seller_config}_{buyer_config}.docx`

Where:
- `land_source`: Either "letter_c" or "certificate"
- `seller_config`:
  - `single_seller` - Regular single seller
  - `multiple_sellers` - Multiple sellers
  - `single_seller_with_consent` - Single seller with consent person
  - `heir_sellers` - Heir seller(s)
- `buyer_config`: Either "single_buyer" or "multiple_buyers"

Examples:
- `letter_c/multiple_sellers_single_buyer.docx`
- `letter_c/single_seller_with_consent_multiple_buyers.docx`
- `letter_c/heir_sellers_single_buyer.docx`

### For Other Types:
Templates should be named: `{type_code}_{seller_config}_{buyer_config}.docx`

Where:
- `type_code`: The land title type code (e.g., "grant", "inheritance")
- `seller_config`: Either "single_seller" or "multiple_sellers"
- `buyer_config`: Either "single_buyer" or "multiple_buyers"

Example: `grant_multiple_sellers_single_buyer.docx`

## Available Placeholders

### PPAT (Notary) Information
- `${ppat_name}` - PPAT/Notary name

**PPAT Address (separate components):**
- `${ppat_road}` - Road/Street name
- `${ppat_rt}` - RT number
- `${ppat_rw}` - RW number
- `${ppat_village}` - Village (Kelurahan/Desa)
- `${ppat_district}` - District (Kecamatan)
- `${ppat_city}` - City (Kota/Kabupaten)
- `${ppat_province}` - Province (Provinsi)

### Document Information
- `${document_number}` - Document number (format: number/year)
- `${document_day}` - Day name in Indonesian (e.g., "Senin")
- `${document_date}` - Day of the month (e.g., "22")
- `${document_month}` - Month name in Indonesian (e.g., "November")
- `${document_year}` - Year (e.g., "2025")
- `${document_year_words}` - Year in Indonesian words

### Seller Information

For single seller templates:
- `${seller_name}` - Seller's full name
- `${seller_birthplace}` - Seller's birthplace
- `${seller_birthdate}` - Seller's birthdate in Indonesian format
- `${seller_age}` - Seller's age (calculated: current year - birth year) with "tahun"
- `${seller_occupation}` - Seller's occupation
- `${seller_national_id_number}` - Seller's National ID (KTP)

**Seller Address (separate components):**
- `${seller_road}` - Road/Street name
- `${seller_rt}` - RT number
- `${seller_rw}` - RW number
- `${seller_village}` - Village (Kelurahan/Desa)
- `${seller_district}` - District (Kecamatan)
- `${seller_city}` - City (Kota/Kabupaten)
- `${seller_province}` - Province (Provinsi)

For multiple sellers templates (use row cloning with #1, #2, etc.):
- `${seller_name#1}`, `${seller_name#2}`, etc.
- `${seller_birthplace#1}`, `${seller_birthdate#1}`, `${seller_age#1}`, etc.
- `${seller_occupation#1}`, `${seller_national_id_number#1}`, etc.
- `${seller_road#1}`, `${seller_rt#1}`, `${seller_rw#1}`, etc.
- `${seller_village#1}`, `${seller_district#1}`, `${seller_city#1}`, `${seller_province#1}`, etc.

### Consent Person Information (for templates with consent)
- `${consent_name}` - Consent person's full name
- `${consent_birthplace}` - Consent person's birthplace
- `${consent_birthdate}` - Consent person's birthdate in Indonesian format
- `${consent_age}` - Consent person's age (calculated: current year - birth year) with "tahun"
- `${consent_occupation}` - Consent person's occupation
- `${consent_national_id_number}` - Consent person's National ID (KTP)

**Consent Address (separate components):**
- `${consent_road}` - Road/Street name
- `${consent_rt}` - RT number
- `${consent_rw}` - RW number
- `${consent_village}` - Village (Kelurahan/Desa)
- `${consent_district}` - District (Kecamatan)
- `${consent_city}` - City (Kota/Kabupaten)
- `${consent_province}` - Province (Provinsi)

### Buyer Information

For single buyer templates:
- `${buyer_name}` - Buyer's full name
- `${buyer_birthplace}` - Buyer's birthplace
- `${buyer_birthdate}` - Buyer's birthdate in Indonesian format
- `${buyer_age}` - Buyer's age (calculated: current year - birth year) with "tahun"
- `${buyer_occupation}` - Buyer's occupation
- `${buyer_national_id_number}` - Buyer's National ID (KTP)

**Buyer Address (separate components):**
- `${buyer_road}` - Road/Street name
- `${buyer_rt}` - RT number
- `${buyer_rw}` - RW number
- `${buyer_village}` - Village (Kelurahan/Desa)
- `${buyer_district}` - District (Kecamatan)
- `${buyer_city}` - City (Kota/Kabupaten)
- `${buyer_province}` - Province (Provinsi)

For multiple buyers templates (use row cloning with #1, #2, etc.):
- `${buyer_name#1}`, `${buyer_name#2}`, etc.
- `${buyer_birthplace#1}`, `${buyer_birthdate#1}`, `${buyer_age#1}`, etc.
- `${buyer_occupation#1}`, `${buyer_national_id_number#1}`, etc.
- `${buyer_road#1}`, `${buyer_rt#1}`, `${buyer_rw#1}`, etc.
- `${buyer_village#1}`, `${buyer_district#1}`, `${buyer_city#1}`, `${buyer_province#1}`, etc.

### Land Information (SPPT)
- `${sppt_number}` - SPPT number
- `${sppt_year}` - SPPT year
- `${sppt_owner}` - SPPT owner name
- `${sppt_block}` - SPPT block number
- `${sppt_land_area}` - SPPT land area
- `${sppt_building_area}` - SPPT building area
- `${sppt_village}` - Village name from SPPT (formatted in Title Case)

### Land Information (Letter C)
- `${letter_c_name}` - Letter C name
- `${letter_c_number}` - Letter C number
- `${letter_c_persil}` - Persil number
- `${letter_c_class}` - Class
- `${letter_c_land_area}` - Land area
- `${letter_c_date}` - Letter C date in Indonesian format

### Land Area and Borders
- `${land_area}` - Land area (formatted as integer, no decimals)
- `${land_area_words}` - Land area in Indonesian words with "Meter Persegi"
- `${north_border}` - North border description
- `${east_border}` - East border description
- `${south_border}` - South border description
- `${west_border}` - West border description

### Transaction Information
- `${transaction_amount}` - Transaction amount (formatted: 1.000.000)
- `${transaction_amount_words}` - Transaction amount in Indonesian words
- `${pph}` - PPh tax (formatted)
- `${bphtb}` - BPHTB tax (formatted)
- `${adm}` - Administration fee (formatted)
- `${pbb}` - PBB tax (formatted)
- `${adm_certificate}` - Certificate administration fee (formatted)
- `${total_amount}` - Total amount (formatted)

### Witness Information

For numbered witnesses (witness_1, witness_2):
- `${witness_1_name}`, `${witness_2_name}` - Witness names
- `${witness_1_birthplace}`, `${witness_2_birthplace}` - Witness birthplaces
- `${witness_1_birthdate}`, `${witness_2_birthdate}` - Witness birthdates
- `${witness_1_age}`, `${witness_2_age}` - Witness ages (calculated)
- `${witness_1_occupation}`, `${witness_2_occupation}` - Witness occupations
- `${witness_1_national_id_number}`, `${witness_2_national_id_number}` - Witness NIK

**Witness Address (separate components):**
- `${witness_1_road}`, `${witness_2_road}` - Road/Street name
- `${witness_1_rt}`, `${witness_1_rw}` - RT/RW numbers
- `${witness_1_village}`, `${witness_1_district}`, `${witness_1_city}`, `${witness_1_province}`

For dynamic witness count (use row cloning with #1, #2, etc.):
- `${witness_name#1}`, `${witness_name#2}`, etc.
- `${witness_birthplace#1}`, `${witness_birthdate#1}`, `${witness_age#1}`, etc.
- `${witness_occupation#1}`, `${witness_national_id_number#1}`, etc.
- `${witness_road#1}`, `${witness_rt#1}`, `${witness_rw#1}`, etc.
- `${witness_village#1}`, `${witness_district#1}`, `${witness_city#1}`, `${witness_province#1}`, etc.

## Using Row Cloning in Templates

For multiple sellers or witnesses, you can use PHPWord's row cloning feature:

1. Create a table row with the placeholder `${seller_name}` (without #1 suffix)
2. The service will automatically clone this row for each seller
3. Each cloned row will have numbered placeholders (e.g., `${seller_name#1}`, `${seller_name#2}`)

## Format Examples

- **Date Format**: "22 November 2025"
- **Money Format**: "1.000.000" (Indonesian thousands separator)
- **Age Format**: "45 tahun"
- **Address Format**: "RT 01 / RW 02, Kelurahan ABC, Kecamatan XYZ, Kota DEF, Provinsi GHI"

## Notes

- All placeholders use `${variable_name}` format for PHPWord TemplateProcessor
- Missing data will display as "-"
- Number-to-words conversion is in Indonesian language
- All dates are formatted in Indonesian format

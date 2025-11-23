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
- `${ppat_address}` - PPAT/Notary full address

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
- `${seller_age}` - Seller's age with "tahun"
- `${seller_occupation}` - Seller's occupation
- `${seller_national_id_number}` - Seller's National ID (KTP)
- `${seller_address}` - Seller's full address

For multiple sellers templates (use row cloning):
- `${seller_name#1}`, `${seller_name#2}`, etc.
- `${seller_birthplace#1}`, `${seller_birthplace#2}`, etc.
- `${seller_birthdate#1}`, `${seller_birthdate#2}`, etc.
- `${seller_age#1}`, `${seller_age#2}`, etc.
- `${seller_occupation#1}`, `${seller_occupation#2}`, etc.
- `${seller_national_id_number#1}`, `${seller_national_id_number#2}`, etc.
- `${seller_address#1}`, `${seller_address#2}`, etc.

### Consent Person Information (for templates with consent)
- `${consent_name}` - Consent person's full name
- `${consent_birthplace}` - Consent person's birthplace
- `${consent_birthdate}` - Consent person's birthdate in Indonesian format
- `${consent_age}` - Consent person's age with "tahun"
- `${consent_occupation}` - Consent person's occupation
- `${consent_national_id_number}` - Consent person's National ID (KTP)
- `${consent_address}` - Consent person's full address

### Buyer Information

For single buyer templates:
- `${buyer_name}` - Buyer's full name
- `${buyer_birthplace}` - Buyer's birthplace
- `${buyer_birthdate}` - Buyer's birthdate in Indonesian format
- `${buyer_age}` - Buyer's age with "tahun"
- `${buyer_occupation}` - Buyer's occupation
- `${buyer_national_id_number}` - Buyer's National ID (KTP)
- `${buyer_address}` - Buyer's full address

For multiple buyers templates (use row cloning):
- `${buyer_name#1}`, `${buyer_name#2}`, etc.
- `${buyer_birthplace#1}`, `${buyer_birthplace#2}`, etc.
- `${buyer_birthdate#1}`, `${buyer_birthdate#2}`, etc.
- `${buyer_age#1}`, `${buyer_age#2}`, etc.
- `${buyer_occupation#1}`, `${buyer_occupation#2}`, etc.
- `${buyer_national_id_number#1}`, `${buyer_national_id_number#2}`, etc.
- `${buyer_address#1}`, `${buyer_address#2}`, etc.

### Land Information (SPPT)
- `${sppt_number}` - SPPT number
- `${sppt_year}` - SPPT year
- `${sppt_owner}` - SPPT owner name
- `${sppt_block}` - SPPT block number
- `${sppt_land_area}` - SPPT land area
- `${sppt_building_area}` - SPPT building area
- `${sppt_village}` - Village name from SPPT

### Land Information (Letter C)
- `${letter_c_name}` - Letter C name
- `${letter_c_number}` - Letter C number
- `${letter_c_persil}` - Persil number
- `${letter_c_class}` - Class
- `${letter_c_land_area}` - Land area
- `${letter_c_date}` - Letter C date in Indonesian format

### Land Area and Borders
- `${land_area}` - Land area in m²
- `${land_area_words}` - Land area in Indonesian words
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
- `${witness_1_age}`, `${witness_2_age}` - Witness ages
- `${witness_1_occupation}`, `${witness_2_occupation}` - Witness occupations
- `${witness_1_national_id_number}`, `${witness_2_national_id_number}` - Witness NIK
- `${witness_1_address}`, `${witness_2_address}` - Witness addresses

For dynamic witness count (use row cloning with `${witness_name}`):
- `${witness_name#1}`, `${witness_name#2}`, etc.
- `${witness_birthplace#1}`, `${witness_birthplace#2}`, etc.
- Similar pattern for other witness fields

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

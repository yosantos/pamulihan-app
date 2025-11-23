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

### Heir Details (for Inheritance Cases)
- `${heir_from_name}` - Name of the deceased person (who the property is inherited from)
- `${death_place}` - Place where the deceased person died
- `${death_date}` - Date of death in Indonesian format
- `${death_certificate_number}` - Death certificate number
- `${death_certificate_issuer}` - Issuer of the death certificate
- `${death_certificate_date}` - Date of death certificate issuance in Indonesian format

**Note:** These placeholders are only filled when `is_heir` is true. Otherwise, they will be filled with "-".

### Seller Information

**Basic seller placeholders (works for both single and multiple sellers):**
- `${seller_name}` - First seller's full name
- `${seller_birthplace}` - First seller's birthplace
- `${seller_birthdate}` - First seller's birthdate in Indonesian format
- `${seller_age}` - First seller's age (calculated: current year - birth year) with "tahun"
- `${seller_occupation}` - First seller's occupation
- `${seller_national_id_number}` - First seller's National ID (KTP)

**Seller Address Components:**
- `${seller_road}` - First seller's road/street name
- `${seller_rt}` - First seller's RT number
- `${seller_rw}` - First seller's RW number
- `${seller_village}` - First seller's village (Kelurahan/Desa)
- `${seller_district}` - First seller's district (Kecamatan)
- `${seller_city}` - First seller's city (Kota/Kabupaten)
- `${seller_province}` - First seller's province (Provinsi)

**For multiple sellers, use one of these formats:**

**Option 1: Row cloning (for tables):**
- `${seller_name#1}`, `${seller_name#2}`, etc.
- `${seller_birthplace#1}`, `${seller_birthdate#1}`, `${seller_age#1}`, etc.
- `${seller_road#1}`, `${seller_rt#1}`, `${seller_rw#1}`, etc.

**Option 2: Numbered placeholders (for non-table layouts):**
- `${seller_1_name}`, `${seller_2_name}`, `${seller_3_name}`, etc.
- `${seller_1_birthplace}`, `${seller_1_birthdate}`, `${seller_1_age}`, etc.
- `${seller_1_road}`, `${seller_1_rt}`, `${seller_1_rw}`, etc.
- `${seller_1_village}`, `${seller_1_district}`, `${seller_1_city}`, `${seller_1_province}`, etc.

**Note:** Both `${seller_name}` and `${seller_1_name}` will show the same (first) seller for backward compatibility.

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

**Basic buyer placeholders (works for both single and multiple buyers):**
- `${buyer_name}` - First buyer's full name
- `${buyer_birthplace}` - First buyer's birthplace
- `${buyer_birthdate}` - First buyer's birthdate in Indonesian format
- `${buyer_age}` - First buyer's age (calculated: current year - birth year) with "tahun"
- `${buyer_occupation}` - First buyer's occupation
- `${buyer_national_id_number}` - First buyer's National ID (KTP)

**Buyer Address Components:**
- `${buyer_road}` - First buyer's road/street name
- `${buyer_rt}` - First buyer's RT number
- `${buyer_rw}` - First buyer's RW number
- `${buyer_village}` - First buyer's village (Kelurahan/Desa)
- `${buyer_district}` - First buyer's district (Kecamatan)
- `${buyer_city}` - First buyer's city (Kota/Kabupaten)
- `${buyer_province}` - First buyer's province (Provinsi)

**For multiple buyers, use one of these formats:**

**Option 1: Row cloning (for tables):**
- `${buyer_name#1}`, `${buyer_name#2}`, etc.
- `${buyer_birthplace#1}`, `${buyer_birthdate#1}`, `${buyer_age#1}`, etc.
- `${buyer_road#1}`, `${buyer_rt#1}`, `${buyer_rw#1}`, etc.

**Option 2: Numbered placeholders (for non-table layouts):**
- `${buyer_1_name}`, `${buyer_2_name}`, `${buyer_3_name}`, etc.
- `${buyer_1_birthplace}`, `${buyer_1_birthdate}`, `${buyer_1_age}`, etc.
- `${buyer_1_road}`, `${buyer_1_rt}`, `${buyer_1_rw}`, etc.
- `${buyer_1_village}`, `${buyer_1_district}`, `${buyer_1_city}`, `${buyer_1_province}`, etc.

**Note:** Both `${buyer_name}` and `${buyer_1_name}` will show the same (first) buyer for backward compatibility.

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

## Using Placeholders in Templates

### Placeholder Repetition
**Important:** You can use the same placeholder multiple times in your document. For example:
- Use `${seller_name}` in the main content section
- Use `${seller_name}` again in the signature section
- Both will be replaced with the same value

This is useful for signature forms where you need to repeat party names.

### Row Cloning for Multiple Records

For multiple sellers, buyers, or witnesses, you can use PHPWord's row cloning feature:

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

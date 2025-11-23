# Grant (Hibah) Templates

This directory should contain Word document templates for Grant (Hibah) land title documents.

## Required Templates

At minimum, create:
- `grant_multiple_sellers_single_buyer.docx` (fallback template)

## Recommended Templates

For complete coverage, create:
- `grant_single_seller_single_buyer.docx` - One grantor to one grantee
- `grant_multiple_sellers_single_buyer.docx` - Multiple grantors to one grantee
- `grant_single_seller_multiple_buyers.docx` - One grantor to multiple grantees
- `grant_multiple_sellers_multiple_buyers.docx` - Multiple grantors to multiple grantees

## Template Naming

Follow the pattern: `grant_{seller_config}_{buyer_config}.docx`

Where:
- `seller_config`: `single_seller` or `multiple_sellers`
- `buyer_config`: `single_buyer` or `multiple_buyers`

## Grant-Specific Considerations

When creating grant templates, consider:

1. **Terminology**: Use "Pemberi Hibah" (grantor) instead of "Penjual" (seller)
2. **Terminology**: Use "Penerima Hibah" (grantee) instead of "Pembeli" (buyer)
3. **Transaction Amount**: May be zero or nominal value
4. **Tax Structure**: Different tax implications than sale/purchase
5. **Family Relations**: Often include family relationship information

## Available Placeholders

All placeholders listed in `resources/templates/land_titles/TEMPLATE_PLACEHOLDERS.md` are available.

Key placeholders for grants:
- Grantor info: `${seller_name}`, `${seller_address}`, etc.
- Grantee info: `${buyer_name}`, `${buyer_address}`, etc.
- Land info: `${land_area}`, `${sppt_number}`, borders, etc.
- Document info: `${document_number}`, `${document_date}`, etc.

## Getting Started

1. Create a new Word document
2. Design the grant agreement layout
3. Replace dynamic data with placeholders using `${placeholder_name}` format
4. Save as `.docx` in this directory
5. Test by generating a document from the admin panel

## Example Reference

Refer to the sale_purchase templates for structure examples, but adapt the language for grants.

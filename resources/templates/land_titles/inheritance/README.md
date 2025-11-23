# Inheritance (Waris) Templates

This directory should contain Word document templates for Inheritance (Waris) land title documents.

## Required Templates

At minimum, create:
- `inheritance_multiple_sellers_single_buyer.docx` (fallback template)

## Recommended Templates

For complete coverage, create:
- `inheritance_single_seller_single_buyer.docx` - One deceased's estate to one heir
- `inheritance_multiple_sellers_single_buyer.docx` - Multiple heirs transferring to one heir
- `inheritance_single_seller_multiple_buyers.docx` - One heir to multiple heirs
- `inheritance_multiple_sellers_multiple_buyers.docx` - Multiple heirs to multiple heirs

## Template Naming

Follow the pattern: `inheritance_{seller_config}_{buyer_config}.docx`

Where:
- `seller_config`: `single_seller` or `multiple_sellers`
- `buyer_config`: `single_buyer` or `multiple_buyers`

## Inheritance-Specific Considerations

When creating inheritance templates, consider:

1. **Terminology**: Use "Pewaris" (deceased/testator) or "Ahli Waris" (heir)
2. **Death Certificate**: Reference to death certificate information
3. **Family Tree**: May need to show relationship to deceased
4. **Multiple Heirs**: Common to have multiple heirs dividing property
5. **Legal Portions**: Islamic/legal inheritance portions (if applicable)
6. **Transaction Amount**: Often based on NJOP or appraisal value

## Available Placeholders

All placeholders listed in `resources/templates/land_titles/TEMPLATE_PLACEHOLDERS.md` are available.

Key placeholders for inheritance:
- Heir/seller info: `${seller_name}`, `${seller_address}`, etc.
- New owner/buyer info: `${buyer_name}`, `${buyer_address}`, etc.
- Land info: `${land_area}`, `${sppt_number}`, borders, etc.
- Document info: `${document_number}`, `${document_date}`, etc.
- Transaction: `${transaction_amount}` (inheritance value)

## Additional Fields Needed

For inheritance documents, you may want to add these fields to the User model in the future:
- Relationship to deceased
- Death certificate number
- Death date
- Inheritance portion/percentage

## Getting Started

1. Create a new Word document
2. Design the inheritance certificate/agreement layout
3. Replace dynamic data with placeholders using `${placeholder_name}` format
4. Save as `.docx` in this directory
5. Test by generating a document from the admin panel

## Legal Note

Inheritance documents often require specific legal language and may need to comply with:
- Indonesian inheritance law
- Islamic inheritance law (if applicable)
- Regional regulations

Consult with legal professionals when creating these templates.

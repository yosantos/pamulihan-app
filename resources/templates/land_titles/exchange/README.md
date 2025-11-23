# Exchange (Tukar Menukar) Templates

This directory should contain Word document templates for Exchange (Tukar Menukar) land title documents.

## Required Templates

At minimum, create:
- `exchange_multiple_sellers_single_buyer.docx` (fallback template)

## Recommended Templates

For complete coverage, create:
- `exchange_single_seller_single_buyer.docx` - One party to one party exchange
- `exchange_multiple_sellers_single_buyer.docx` - Multiple parties to one party
- `exchange_single_seller_multiple_buyers.docx` - One party to multiple parties
- `exchange_multiple_sellers_multiple_buyers.docx` - Multiple parties to multiple parties

## Template Naming

Follow the pattern: `exchange_{seller_config}_{buyer_config}.docx`

Where:
- `seller_config`: `single_seller` or `multiple_sellers`
- `buyer_config`: `single_buyer` or `multiple_buyers`

## Exchange-Specific Considerations

When creating exchange templates, consider:

1. **Terminology**: Use "Pihak Pertama" and "Pihak Kedua" (First Party / Second Party)
2. **Dual Property Info**: Need information for BOTH properties being exchanged
3. **Value Difference**: May include compensation if property values differ
4. **Transaction Amount**: Represents the difference in value (if any)
5. **Dual Land Descriptions**: Need borders, SPPT, etc. for both properties

## Available Placeholders

All placeholders listed in `resources/templates/land_titles/TEMPLATE_PLACEHOLDERS.md` are available.

**Current Limitation**: The current system tracks only ONE property. For proper exchange documents, you may need to:
1. Create two land title records (one for each property)
2. Link them as related exchanges
3. Or extend the model to support dual property information

Key placeholders for exchange:
- Party 1 info: `${seller_name}`, `${seller_address}`, etc.
- Party 2 info: `${buyer_name}`, `${buyer_address}`, etc.
- Land info: `${land_area}`, `${sppt_number}`, borders, etc.
- Document info: `${document_number}`, `${document_date}`, etc.
- Value difference: `${transaction_amount}`

## Future Enhancements

For proper exchange document support, consider adding:
- Second property information fields
- Exchange ratio or value comparison
- Linked exchange records
- Dual SPPT/Letter C references

## Getting Started

1. Create a new Word document
2. Design the exchange agreement layout
3. Note: Currently can track one property - adapt template accordingly
4. Replace dynamic data with placeholders using `${placeholder_name}` format
5. Save as `.docx` in this directory
6. Test by generating a document from the admin panel

## Workaround for Dual Properties

Until dual property support is added:
1. Use the current property fields for Property A (being given away)
2. Add manual text fields in the template for Property B details
3. Or generate two separate documents (one for each transfer)

## Legal Note

Exchange agreements require specific legal language and must comply with Indonesian land law regarding property exchanges.

Consult with legal professionals when creating these templates.

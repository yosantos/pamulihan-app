<?php

namespace App\Services;

use App\Models\WhatsAppCampaign;
use InvalidArgumentException;

class TemplateParserService
{
    /**
     * Parse template and replace all placeholders with values.
     *
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function parseTemplate(string $template, array $variables): string
    {
        $parsedTemplate = $template;

        foreach ($variables as $key => $value) {
            $placeholder = '[' . $key . ']';
            $parsedTemplate = str_replace($placeholder, $value, $parsedTemplate);
        }

        return $parsedTemplate;
    }

    /**
     * Extract all placeholders from template.
     * Returns array of variable names without brackets.
     *
     * @param string $template
     * @return array
     */
    public function extractPlaceholders(string $template): array
    {
        preg_match_all('/\[([^\]]+)\]/', $template, $matches);

        return $matches[1] ?? [];
    }

    /**
     * Validate that all required variables are provided.
     *
     * @param string $template
     * @param array $providedVariables
     * @return array Array of missing variables
     */
    public function validateVariables(string $template, array $providedVariables): array
    {
        $requiredVariables = $this->extractPlaceholders($template);
        $providedKeys = array_keys($providedVariables);

        return array_diff($requiredVariables, $providedKeys);
    }

    /**
     * Get static variables from campaign.
     *
     * @param WhatsAppCampaign $campaign
     * @return array
     */
    public function getStaticVariables(WhatsAppCampaign $campaign): array
    {
        return $campaign->getStaticVariables();
    }

    /**
     * Get dynamic variables from campaign.
     *
     * @param WhatsAppCampaign $campaign
     * @return array
     */
    public function getDynamicVariables(WhatsAppCampaign $campaign): array
    {
        return $campaign->getDynamicVariables();
    }

    /**
     * Merge static and dynamic variables for template parsing.
     *
     * @param WhatsAppCampaign $campaign
     * @param array $dynamicValues
     * @return array
     */
    public function mergeVariables(WhatsAppCampaign $campaign, array $dynamicValues): array
    {
        $staticVariables = $this->getStaticVariables($campaign);

        return array_merge($staticVariables, $dynamicValues);
    }

    /**
     * Parse campaign template with provided dynamic values.
     *
     * @param WhatsAppCampaign $campaign
     * @param array $dynamicValues
     * @return string
     * @throws InvalidArgumentException
     */
    public function parseCampaignTemplate(WhatsAppCampaign $campaign, array $dynamicValues): string
    {
        // Merge static and dynamic variables
        $allVariables = $this->mergeVariables($campaign, $dynamicValues);

        // Validate all required variables are present
        $missingVariables = $this->validateVariables($campaign->template, $allVariables);

        if (!empty($missingVariables)) {
            throw new InvalidArgumentException(
                'Missing required variables: ' . implode(', ', $missingVariables)
            );
        }

        // Parse the template
        return $this->parseTemplate($campaign->template, $allVariables);
    }

    /**
     * Validate dynamic values against campaign variable definitions.
     *
     * @param WhatsAppCampaign $campaign
     * @param array $dynamicValues
     * @return array Array of validation errors
     */
    public function validateDynamicValues(WhatsAppCampaign $campaign, array $dynamicValues): array
    {
        $errors = [];
        $requiredDynamicVars = $campaign->getRequiredDynamicVariables();

        foreach ($requiredDynamicVars as $variable) {
            $varName = $variable['name'];

            if (!isset($dynamicValues[$varName]) || empty($dynamicValues[$varName])) {
                $errors[$varName] = ($variable['label'] ?? $varName) . ' is required.';
            }
        }

        return $errors;
    }

    /**
     * Get a preview of the parsed template with sample data.
     *
     * @param string $template
     * @param array $sampleVariables
     * @return string
     */
    public function getTemplatePreview(string $template, array $sampleVariables = []): string
    {
        $placeholders = $this->extractPlaceholders($template);
        $previewVariables = [];

        foreach ($placeholders as $placeholder) {
            $previewVariables[$placeholder] = $sampleVariables[$placeholder] ?? '[' . $placeholder . ']';
        }

        return $this->parseTemplate($template, $previewVariables);
    }

    /**
     * Generate variable definitions from template.
     * Useful for auto-detecting variables from a template string.
     *
     * @param string $template
     * @param string $companyName
     * @return array
     */
    public function generateVariableDefinitions(string $template, string $companyName = ''): array
    {
        $placeholders = $this->extractPlaceholders($template);
        $variables = [];

        foreach ($placeholders as $placeholder) {
            // Check if it's the company name variable
            if ($placeholder === 'Name_Company') {
                $variables[] = [
                    'name' => $placeholder,
                    'type' => 'static',
                    'label' => 'Company Name',
                    'required' => true,
                    'default_value' => $companyName,
                ];
            } else {
                // Default to dynamic variable
                $variables[] = [
                    'name' => $placeholder,
                    'type' => 'dynamic',
                    'label' => ucwords(str_replace('_', ' ', $placeholder)),
                    'required' => true,
                    'default_value' => null,
                ];
            }
        }

        return $variables;
    }
}

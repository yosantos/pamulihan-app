<?php

namespace App\Services;

class TemplateVariableParser
{
    /**
     * Extract all variables from a template string.
     * Variables are defined as text within square brackets, e.g., [variable_name]
     *
     * @param string $template
     * @return array Array of unique variable names
     */
    public function extractVariables(string $template): array
    {
        preg_match_all('/\[(\w+)\]/', $template, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Replace variables in a template with provided values.
     *
     * @param string $template The template string with [variable] placeholders
     * @param array $values Array of variable_name => value pairs
     * @return string The template with variables replaced
     */
    public function replaceVariables(string $template, array $values): string
    {
        $result = $template;

        foreach ($values as $variable => $value) {
            $result = str_replace('[' . $variable . ']', $value, $result);
        }

        return $result;
    }

    /**
     * Validate that all variables in the template have values provided.
     *
     * @param string $template The template string
     * @param array $values Array of variable_name => value pairs
     * @return bool True if all variables are provided, false otherwise
     */
    public function validateVariables(string $template, array $values): bool
    {
        $required = $this->extractVariables($template);

        foreach ($required as $variable) {
            if (!isset($values[$variable]) || $values[$variable] === '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the list of missing variables.
     *
     * @param string $template The template string
     * @param array $values Array of variable_name => value pairs
     * @return array Array of missing variable names
     */
    public function getMissingVariables(string $template, array $values): array
    {
        $required = $this->extractVariables($template);
        $missing = [];

        foreach ($required as $variable) {
            if (!isset($values[$variable]) || $values[$variable] === '') {
                $missing[] = $variable;
            }
        }

        return $missing;
    }

    /**
     * Get the regex pattern used to match variable placeholders.
     *
     * @return string The regex pattern
     */
    public function getPlaceholderPattern(): string
    {
        return '/\[(\w+)\]/';
    }

    /**
     * Preview a template with sample values.
     * If no values are provided, placeholders remain as is.
     *
     * @param string $template The template string
     * @param array $sampleValues Optional sample values
     * @return string The preview string
     */
    public function preview(string $template, array $sampleValues = []): string
    {
        if (empty($sampleValues)) {
            return $template;
        }

        return $this->replaceVariables($template, $sampleValues);
    }

    /**
     * Count the number of variables in a template.
     *
     * @param string $template
     * @return int
     */
    public function countVariables(string $template): int
    {
        return count($this->extractVariables($template));
    }

    /**
     * Check if a template contains a specific variable.
     *
     * @param string $template
     * @param string $variable
     * @return bool
     */
    public function hasVariable(string $template, string $variable): bool
    {
        return in_array($variable, $this->extractVariables($template));
    }
}

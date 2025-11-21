<?php

namespace App\Domains\Post\Services;

/**
 * Class ComponentService.
 */
class ComponentService
{
    private $fieldRules;

    public function __construct()
    {
        $globalRules = ['nullable'];
        $this->fieldRules = [
            'text' => array_merge($globalRules, [
                'max:1000',
            ]),
            'image' => array_merge($globalRules, [
//                'mimes:jpg,png',
//                'max:800',
            ]),
            'url' => array_merge($globalRules, [
                'string',
            ]),
        ];
    }

    /**
     * @return array
     */
    public function getComponentRules($components)
    {
        $rules = [];
        foreach ($components as $component) {
            $rulesComponent = $this->getFieldRules($component['fields'], $component['keyName']);
            $rules = array_merge($rules, $rulesComponent);
        }

        return $rules;
    }

    public function getComponentRulesLanguage($components, $templateInfo)
    {
        $rules = [];
        foreach ($components as $component) {
            $rulesComponent = $this->getFieldRulesLanguage($component['fields'], $component['keyName'], $templateInfo);
            $rules = array_merge($rules, $rulesComponent);
        }

        return $rules;
    }
    public function getFieldRulesLanguage($fields, String $componentName, $templateInfo, Bool $repeater = false)
{
    $rules = [];
    foreach ($fields as $field) {
        $fieldSeparator = ($repeater) ? '.*.' : '.';
        $fieldName = $componentName . $fieldSeparator . $field['name'];

        if ($field['type'] == 'repeater') {
            // Handle repeater fields
            $rulesRepeater = $this->getFieldRulesLanguage($field['list'], $fieldName, $templateInfo, true);
            $rules = array_merge($rules, $rulesRepeater);
            $rules[$fieldName] = ['nullable'];
        } else {
            $fieldRules = $this->getFieldRule($field);
            
            if ($templateInfo['multilanguage'] === 'true') {
                foreach ($templateInfo['lang_option'] as $langOption) {
                    if ($langOption['master'] === 'true') {
                        // Default language
                        $rules[$fieldName . '_' . $langOption['value']] = $fieldRules;
                    } else {
                        // Other languages
                        $rules[$fieldName . '_' . $langOption['value']] = $fieldRules;
                    }
                }
            } else {
                // Non-multilingual field
                $rules[$fieldName] = $fieldRules;
            }
        }
    }

    return $rules;
}

    public function getFieldRules($fields, String $componentName, Bool $repeater = false)
    {
        $rules = [];
        foreach ($fields as $field) {
            $fieldSeparator = ($repeater) ? '.*.' : '.';
            $fieldName = $componentName . $fieldSeparator . $field['name'];
            if ($field['type'] == 'repeater') {
                // $rulesRepeater = $this->getFieldRules($field['list'], $fieldName, true);
                // $rules = array_merge($rules, $rulesRepeater);
                $rules[$fieldName] = ['nullable'];
            } else {
                $fieldRules = $this->getFieldRule($field);
                $rules[$fieldName] = $fieldRules;
            }
        }

        return $rules;
    }

    public function getFieldRule($field)
    {
        $fieldRulesTemplate = isset($field['rules']) ? $field['rules'] : [];
        $fieldRulesDefault = isset($this->fieldRules[$field['type']]) ? $this->fieldRules[$field['type']] : [];
        $fieldRules = array_merge($fieldRulesDefault, $fieldRulesTemplate);

        return $fieldRules;
    }
}

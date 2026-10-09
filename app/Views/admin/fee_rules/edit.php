<?php
/**
 * Admin - Edit Fee Rule
 * Reuses the create form with pre-populated values.
 */
$editMode = true;
$formAction = BASE_URL . '/admin/fee-rules/update';
// Decode JSON fields for the form
$rule['application_states'] = is_array($rule['application_states'])
    ? $rule['application_states']
    : (json_decode($rule['application_states'] ?? '[]', true) ?: []);
$rule['weight_slabs'] = is_array($rule['weight_slabs'])
    ? $rule['weight_slabs']
    : (json_decode($rule['weight_slabs'] ?? '[]', true) ?: []);

include __DIR__ . '/create.php';

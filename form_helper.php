<?php
require_once 'database.php';

function escape($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function buildContactForm($values = [], $buttonLabel = 'Добавить', $formName = 'form_add', $contactId = null)
{
    $fields = [
        'surname' => ['label' => 'Фамилия', 'type' => 'text', 'required' => true],
        'name' => ['label' => 'Имя', 'type' => 'text', 'required' => true],
        'lastname' => ['label' => 'Отчество', 'type' => 'text', 'required' => false],
        'gender' => ['label' => 'Пол', 'type' => 'select', 'required' => false],
        'date' => ['label' => 'Дата рождения', 'type' => 'date', 'required' => false],
        'phone' => ['label' => 'Телефон', 'type' => 'text', 'required' => false],
        'location' => ['label' => 'Адрес', 'type' => 'text', 'required' => false],
        'email' => ['label' => 'Email', 'type' => 'email', 'required' => false],
        'comment' => ['label' => 'Комментарий', 'type' => 'textarea', 'required' => false],
    ];

    $html = '<form name="' . escape($formName) . '" method="post">';
    if ($contactId !== null) {
        $html .= '<input type="hidden" name="id" value="' . (int)$contactId . '">';
    }
    $html .= '<div class="column">';

    foreach ($fields as $field => $config) {
        $value = isset($values[$field]) ? $values[$field] : '';
        $label = $config['label'];
        $required = $config['required'] ? ' required' : '';

        $html .= '<div class="add">';
        $html .= '<label>' . escape($label) . '</label>';

        if ($config['type'] === 'select' && $field === 'gender') {
            $html .= buildGenderSelect($value);
        } elseif ($config['type'] === 'textarea') {
            $html .= '<textarea name="' . escape($field) . '" placeholder="Краткий комментарий"' . $required . '>' . escape($value) . '</textarea>';
        } else {
            $html .= '<input type="' . escape($config['type']) . '" name="' . escape($field) . '" placeholder="' . escape($label) . '" value="' . escape($value) . '"' . $required . '>';
        }

        $html .= '</div>';
    }

    $html .= '<button type="submit" value="' . escape($buttonLabel) . '" name="button" class="form-btn">' . escape($buttonLabel) . '</button>';
    $html .= '</div>';
    $html .= '</form>';

    return $html;
}

function buildGenderSelect($current = '')
{
    $html = '<select name="gender">';
    $html .= '<option value="">Выберите пол</option>';

    $options = ['мужской', 'женский'];
    foreach ($options as $option) {
        $selected = ($current === $option) ? ' selected' : '';
        $html .= '<option value="' . escape($option) . '"' . $selected . '>' . escape($option) . '</option>';
    }

    $html .= '</select>';
    return $html;
}

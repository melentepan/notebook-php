<?php
function renderViewer($sort = 'id', $page = 1)
{
  require_once 'database.php';
  $db = getDB();

  $per_page = 10;
  $offset = ($page - 1) * $per_page;

  $allowed_sorts = ['id', 'surname', 'date_birth'];
  if (!in_array($sort, $allowed_sorts)) {
    $sort = 'id';
  }

  $count_stmt = $db->query("SELECT COUNT(*) FROM contacts");
  $total_records = $count_stmt->fetchColumn();
  $total_pages = ceil($total_records / $per_page);

  $stmt = $db->prepare("SELECT * FROM contacts ORDER BY $sort ASC LIMIT :limit OFFSET :offset");
  $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  $contacts = $stmt->fetchAll();

  $html = '<table>';
  $html .= '<thead><tr>';
  $html .= '<th>Фамилия</th>';
  $html .= '<th>Имя</th>';
  $html .= '<th>Отчество</th>';
  $html .= '<th>Пол</th>';
  $html .= '<th>Дата рождения</th>';
  $html .= '<th>Телефон</th>';
  $html .= '<th>Адрес</th>';
  $html .= '<th>Email</th>';
  $html .= '<th>Комментарий</th>';
  $html .= '</tr></thead>';
  $html .= '<tbody>';

  if (empty($contacts)) {
    $html .= '<tr><td colspan="9" style="text-align: center;">Записей не найдено</td></tr>';
  } else {
    foreach ($contacts as $contact) {
      $html .= '<tr>';
      $html .= '<td>' . $contact['surname'] . '</td>';
      $html .= '<td>' . $contact['name'] . '</td>';
      $html .= '<td>' . (isset($contact['lastname']) ? $contact['lastname'] : '') . '</td>';
      $html .= '<td>' . (isset($contact['gender']) ? $contact['gender'] : '') . '</td>';
      $html .= '<td>' . (isset($contact['date_birth']) ? $contact['date_birth'] : '') . '</td>';
      $html .= '<td>' . (isset($contact['phone']) ? $contact['phone'] : '') . '</td>';
      $html .= '<td>' . (isset($contact['location']) ? $contact['location'] : '') . '</td>';
      $html .= '<td>' . (isset($contact['email']) ? $contact['email'] : '') . '</td>';
      $html .= '<td>' . (isset($contact['comment']) ? $contact['comment'] : '') . '</td>';
      $html .= '</tr>';
    }
  }

  $html .= '</tbody></table>';

  if ($total_pages > 1) {
    $html .= '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
      $class = ($i == $page) ? 'select' : '';
      $html .= '<a href="index.php?page=view&sort=' . $sort . '&p=' . $i . '" class="' . $class . '">' . $i . '</a>';
    }
    $html .= '</div>';
  }

  return $html;
}

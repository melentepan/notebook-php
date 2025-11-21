<?php
function renderDeleteForm()
{
  require_once 'database.php';
  $db = getDB();

  $message = '';
  $deleted_surname = '';

  if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $db->prepare("SELECT surname, name, lastname FROM contacts WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch();

    if ($contact) {
      $deleted_name = $contact['surname'];
      if (!empty($contact['name'])) {
        $deleted_name .= ' ' . mb_substr($contact['name'], 0, 1, 'UTF-8') . '.';
      }
      if (!empty($contact['lastname'])) {
        $deleted_name .= ' ' . mb_substr($contact['lastname'], 0, 1, 'UTF-8') . '.';
      }

      $stmt = $db->prepare("DELETE FROM contacts WHERE id = :id");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        $message = 'Запись с фамилией ' . $deleted_name . ' удалена';
      }
    }
  }

  $stmt = $db->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname, name");
  $contacts = $stmt->fetchAll();

  $html = '';

  if ($message) {
    $html .= '<div class="success" style="padding: 10px; margin: 20px auto; width: 50%; border-radius: 5px; text-align: center;">';
    $html .= $message;
    $html .= '</div>';
  }

  if (!empty($contacts)) {
    $html .= '<div class="div-edit">';
    foreach ($contacts as $contact) {
      $html .= '<a href="index.php?page=delete&id=' . $contact['id'] . '" style="display: block; padding: 5px; margin: 5px 0;">';
      $name = $contact['surname'];
      if (!empty($contact['name'])) {
        $name .= ' ' . mb_substr($contact['name'], 0, 1, 'UTF-8') . '.';
      }
      if (!empty($contact['lastname'])) {
        $name .= ' ' . mb_substr($contact['lastname'], 0, 1, 'UTF-8') . '.';
      }
      $html .= $name;
      $html .= '</a>';
    }
    $html .= '</div>';
  } else {
    $html .= '<p>Записей не найдено</p>';
  }

  return $html;
}

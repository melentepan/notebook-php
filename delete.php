<?php
function renderDeleteForm()
{
  require_once 'database.php';
  $db = getDB();

  $message = '';
  $deleted_surname = '';

  if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $db->prepare("SELECT surname FROM contacts WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch();

    if ($contact) {
      $deleted_surname = $contact['surname'];

      $stmt = $db->prepare("DELETE FROM contacts WHERE id = :id");
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);

      if ($stmt->execute()) {
        $message = 'Запись с фамилией ' . $deleted_surname . ' удалена';
      }
    }
  }

  $stmt = $db->query("SELECT id, surname, name FROM contacts ORDER BY surname, name");
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
      $html .= $contact['surname'] . ' ' . $contact['name'];
      $html .= '</a>';
    }
    $html .= '</div>';
  } else {
    $html .= '<p>Записей не найдено</p>';
  }

  return $html;
}

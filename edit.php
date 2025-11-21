<?php
require_once 'form_helper.php';

function renderEditForm()
{
  require_once 'database.php';
  $db = getDB();

  $selected_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
  $contact = null;

  $stmt = $db->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname, name");
  $all_contacts = $stmt->fetchAll();

  if ($selected_id) {
    $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->bindValue(':id', $selected_id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch();
  } elseif (!empty($all_contacts)) {
    $selected_id = $all_contacts[0]['id'];
    $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->bindValue(':id', $selected_id, PDO::PARAM_INT);
    $stmt->execute();
    $contact = $stmt->fetch();
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['button']) && $_POST['button'] == 'Сохранить' && isset($_POST['id'])) {
    try {
      $stmt = $db->prepare("UPDATE contacts SET 
                surname = :surname, 
                name = :name, 
                lastname = :lastname, 
                gender = :gender, 
                date_birth = :date_birth, 
                phone = :phone, 
                location = :location, 
                email = :email, 
                comment = :comment 
                WHERE id = :id");

      $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
      $stmt->bindValue(':surname', isset($_POST['surname']) ? $_POST['surname'] : '');
      $stmt->bindValue(':name', isset($_POST['name']) ? $_POST['name'] : '');
      $stmt->bindValue(':lastname', isset($_POST['lastname']) ? $_POST['lastname'] : '');
      $stmt->bindValue(':gender', isset($_POST['gender']) ? $_POST['gender'] : '');
      $stmt->bindValue(':date_birth', isset($_POST['date']) ? $_POST['date'] : '');
      $stmt->bindValue(':phone', isset($_POST['phone']) ? $_POST['phone'] : '');
      $stmt->bindValue(':location', isset($_POST['location']) ? $_POST['location'] : '');
      $stmt->bindValue(':email', isset($_POST['email']) ? $_POST['email'] : '');
      $stmt->bindValue(':comment', isset($_POST['comment']) ? $_POST['comment'] : '');

      if ($stmt->execute()) {
        header("Location: index.php?page=edit&id=" . $_POST['id']);
        exit;
      }
    } catch (Exception $e) {
    }
  }

  $html = '<div class="edit-container">';

  if (!empty($all_contacts)) {
    $html .= '<div class="div-edit">';
    foreach ($all_contacts as $c) {
      $class = ($c['id'] == $selected_id) ? 'currentRow' : '';
      $html .= '<a href="index.php?page=edit&id=' . $c['id'] . '" class="' . $class . '">';
      $name = $c['surname'];
      if (!empty($c['name'])) {
        $name .= ' ' . mb_substr($c['name'], 0, 1, 'UTF-8') . '.';
      }
      if (!empty($c['lastname'])) {
        $name .= ' ' . mb_substr($c['lastname'], 0, 1, 'UTF-8') . '.';
      }
      $html .= $name;
      $html .= '</a>';
    }
    $html .= '</div>';
  }

  if ($contact) {
    $values = [
      'surname' => isset($contact['surname']) ? $contact['surname'] : '',
      'name' => isset($contact['name']) ? $contact['name'] : '',
      'lastname' => isset($contact['lastname']) ? $contact['lastname'] : '',
      'gender' => isset($contact['gender']) ? $contact['gender'] : '',
      'date' => isset($contact['date_birth']) ? $contact['date_birth'] : '',
      'phone' => isset($contact['phone']) ? $contact['phone'] : '',
      'location' => isset($contact['location']) ? $contact['location'] : '',
      'email' => isset($contact['email']) ? $contact['email'] : '',
      'comment' => isset($contact['comment']) ? $contact['comment'] : '',
    ];
    
    $html .= buildContactForm($values, 'Сохранить', 'form_edit', $contact['id']);
  } else {
    $html .= '<p>Записей не найдено</p>';
  }

  $html .= '</div>';

  return $html;
}

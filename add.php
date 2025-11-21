<?php
require_once 'form_helper.php';

function renderAddForm()
{
  $message = '';
  $message_class = '';

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['button']) && $_POST['button'] == 'Добавить') {
    require_once 'database.php';
    $db = getDB();

    try {
      $stmt = $db->prepare("INSERT INTO contacts (surname, name, lastname, gender, date_birth, phone, location, email, comment, created_at) 
                                  VALUES (:surname, :name, :lastname, :gender, :date_birth, :phone, :location, :email, :comment, NOW())");

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
        $message = 'Запись добавлена';
        $message_class = 'success';
        $_POST = array();
      } else {
        $message = 'Ошибка: запись не добавлена';
        $message_class = 'error';
      }
    } catch (Exception $e) {
      $message = 'Ошибка: запись не добавлена';
      $message_class = 'error';
    }
  }

  $html = '';
  if ($message) {
    $html .= '<div class="' . $message_class . '" style="padding: 10px; margin: 20px auto; width: 50%; border-radius: 5px; text-align: center;">';
    $html .= $message;
    $html .= '</div>';
  }

  $values = [
    'surname' => isset($_POST['surname']) ? $_POST['surname'] : '',
    'name' => isset($_POST['name']) ? $_POST['name'] : '',
    'lastname' => isset($_POST['lastname']) ? $_POST['lastname'] : '',
    'gender' => isset($_POST['gender']) ? $_POST['gender'] : '',
    'date' => isset($_POST['date']) ? $_POST['date'] : '',
    'phone' => isset($_POST['phone']) ? $_POST['phone'] : '',
    'location' => isset($_POST['location']) ? $_POST['location'] : '',
    'email' => isset($_POST['email']) ? $_POST['email'] : '',
    'comment' => isset($_POST['comment']) ? $_POST['comment'] : '',
  ];

  $html .= buildContactForm($values, 'Добавить', 'form_add');

  return $html;
}

<?php
function renderMenu()
{
  $current_page = isset($_GET['page']) ? $_GET['page'] : 'view';

  $html = '<header>';
  $html .= '<a href="index.php?page=view" class="' . ($current_page == 'view' ? 'select' : '') . '">Просмотр</a>';
  $html .= '<a href="index.php?page=add" class="' . ($current_page == 'add' ? 'select' : '') . '">Добавление записи</a>';
  $html .= '<a href="index.php?page=edit" class="' . ($current_page == 'edit' ? 'select' : '') . '">Редактирование записи</a>';
  $html .= '<a href="index.php?page=delete" class="' . ($current_page == 'delete' ? 'select' : '') . '">Удаление записи</a>';
  $html .= '</header>';

  if ($current_page == 'view') {
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $html .= '<div class="submenu">';
    $html .= '<a href="index.php?page=view&sort=id" class="' . ($sort == 'id' ? 'select' : '') . '">По порядку добавления</a>';
    $html .= '<a href="index.php?page=view&sort=surname" class="' . ($sort == 'surname' ? 'select' : '') . '">По фамилии</a>';
    $html .= '<a href="index.php?page=view&sort=date_birth" class="' . ($sort == 'date_birth' ? 'select' : '') . '">По дате рождения</a>';
    $html .= '</div>';
  }

  return $html;
}

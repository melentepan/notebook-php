<?php
require_once 'menu.php';
require_once 'viewer.php';
require_once 'add.php';
require_once 'edit.php';
require_once 'delete.php';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Записная книжка</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php
	$page = isset($_GET['page']) ? $_GET['page'] : 'view';

	echo renderMenu();
	?>

	<main>
		<?php
		switch ($page) {
			case 'view':
				$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
				$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
				if ($p < 1) $p = 1;
				echo renderViewer($sort, $p);
				break;

			case 'add':
				echo renderAddForm();
				break;

			case 'edit':
				echo renderEditForm();
				break;

			case 'delete':
				echo renderDeleteForm();
				break;

			default:
				$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
				$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
				if ($p < 1) $p = 1;
				echo renderViewer($sort, $p);
				break;
		}
		?>
	</main>

	<footer></footer>
</body>

</html>
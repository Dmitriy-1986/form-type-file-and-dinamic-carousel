<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    $targetDir = "images/";
    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
    
    // Проверяем, что файл был загружен без ошибок
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $fileType = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);
        
        // Проверяем, что файл является изображением
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                $_SESSION['upload_message'] = "<p class='text-primary'>Файл успешно загружен.</p>";
            } else {
                $_SESSION['upload_message'] = "<p class='text-danger'>Произошла ошибка при загрузке файла.</p>";
            }
        } else {
            $_SESSION['upload_message'] = "<p class='text-danger'>Можно загружать только файлы изображений (JPEG, PNG, GIF, WEBP).</p>";
        }
    } else {
        $_SESSION['upload_message'] = "<p class='text-danger'>Файл не был загружен. Пожалуйста, попробуйте еще раз.</p>";
    }

    header("Location: slider.php");
    exit();
}
?>
<!doctype html>
<html lang="ru">
<head>
    <!-- Мета-теги, необходимые для корректного отображения -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Карусель</title>
</head>
<body>

<div class="container p-3">
    <h2>Добавить файл:</h2>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <div class="mt-3">
            <label for="fileToUpload" class="form-label">Выберите файл</label>
            <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
            <?php
            if (isset($_SESSION['upload_message'])) {
                echo $_SESSION['upload_message'];
                unset($_SESSION['upload_message']); // Очистка сообщения после вывода
            }
            ?>
        </div>
        <div class="mt-3">
            <input type="submit" class="btn btn-primary" name="submit" value="Отправить">
        </div>
    </form>
</div>

<div class="container">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $dir = 'images/';
            if (is_dir($dir)) {
                $openDir = opendir($dir);
                $active = true;

                while (($file = readdir($openDir)) !== false) {
                    if ($file != "." && $file != "..") {
                        if ($active) {
                            echo '<div class="carousel-item active">';
                            $active = false;
                        } else {
                            echo '<div class="carousel-item">';
                        }
                        echo '<img class="d-block w-100" style="height: 350px;object-fit: cover;" src="' . $dir . '/' . $file . '" alt="Img">';
                        echo '</div>';
                    }
                }
                closedir($openDir);
            }
            ?>
        </div>
    </div>
</div>

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>

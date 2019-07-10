<?php include_once ('functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Are you home?</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <?php if (count($status) > 0): ?>
    <table class="table">
        <thead>
        <tr>
            <th><?php echo implode('</th><th>', array_keys(current($status))); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($status as $row): array_map('htmlentities', $row); ?>
            <tr>
                <td><?php
                        $row['result'] =  $row['result'] ? "Here" : "Not Here";
                        echo implode('</td><td>', $row);
                        ?>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    <?php endif; ?>
</div>

</body>
</html> 
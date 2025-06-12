<?php
include('db_config.php');

if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);
    $completion_date = date('Y-m-d'); 

    $sql = "UPDATE task 
            SET status = 'Completed', 
                completion_date = '$completion_date'
            WHERE task_id = $task_id";

    if ($conn->query($sql)) {
        header("Location: dashboard.php?msg=Task marked complete");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

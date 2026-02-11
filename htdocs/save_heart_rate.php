<?php
session_start();
include 'db_connect.php'; 

if (isset($_POST['check_intensity'])) {
    $bpm = intval($_POST['bpm_value']); 
    
    // Default IDs base sa setup mo
    $user_id = 1;   
    $device_id = 1; 

    // 1. Determine Category - Para mag-match sa exercise_recommendation table
    if ($bpm >= 140) { 
        $category = "High"; 
        $intensity = "High"; 
    } elseif ($bpm >= 100) { 
        $category = "Medium"; 
        $intensity = "Medium"; 
    } else {
        $category = "Low"; 
        $intensity = "Optimal"; 
    }

    // 2. I-save muna ang Heart Rate Data
    $sql_heart = "INSERT INTO heart_data (user_id, device_id, bpm, intensity) VALUES (?, ?, ?, ?)";
    $stmt_heart = $conn->prepare($sql_heart);
    $stmt_heart->bind_param("iiis", $user_id, $device_id, $bpm, $intensity);

    if ($stmt_heart->execute()) {
        
        // 3. Kumuha lang ng ISANG exercise para hindi mag-doble sa history
        // Nilagyan natin ng LIMIT 1 para siguradong isa lang ang kukunin
        $sql_rec = "SELECT recommendation_id FROM exercise_recommendation WHERE category = ? LIMIT 1";
        $stmt_rec = $conn->prepare($sql_rec);
        $stmt_rec->bind_param("s", $category);
        $stmt_rec->execute();
        $result_rec = $stmt_rec->get_result();

        // 4. I-save ang ISANG nahanap na exercise sa 'user_recommendation_history'
        if ($row = $result_rec->fetch_assoc()) {
            $rec_id = $row['recommendation_id'];
            $sql_hist = "INSERT INTO user_recommendation_history (user_id, recommendation_id, triggered_by_bpm) VALUES (?, ?, ?)";
            $stmt_hist = $conn->prepare($sql_hist);
            $stmt_hist->bind_param("iii", $user_id, $rec_id, $bpm);
            $stmt_hist->execute();
        }

        // Redirect pabalik sa Dashboard
        header("Location: dashboard.php?status=success");
        exit();

    } else {
        die("Error saving heart data: " . $stmt_heart->error); 
    }
}
?>
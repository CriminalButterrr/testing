<?php      
// Include database configuration file  
require_once 'dbConnect.php'; 
 
// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 
 
if($jsonObj->request_type == 'addEditDentist'){ 
    $dentist_data = $jsonObj->dentist_data; 
    $dentist_name = !empty($dentist_data[0])?$dentist_data[0]:''; 
    $address = !empty($dentist_data[1])?$dentist_data[1]:''; 
    $contact_num = !empty($dentist_data[2])?$dentist_data[2]:''; 
    $id = !empty($dentist_data[3])?$dentist_data[3]:0; 
 
    $err = ''; 
    if(empty($dentist_name)){ 
        $err .= "Please enter your client's name.<br/>"; 
    } 
    if(empty($address)){ 
        $err .= 'Please enter your the case.<br/>'; 
    } 
    if(empty($contact_num)){ 
        $err .= 'Please enter the contact number<br/>'; 
    } 
     
    if(!empty($dentist_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update transaction data into the database 
            $sqlQ = "UPDATE transactions SET dentist_name=?,address=?,contact_num=?,modified=NOW() WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("sssi", $dentist_name, $address, $contact_num, $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                $output = [ 
                    'status' => 1, 
                    'msg' => 'Dentist updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Record Update request failed!']); 
            } 
        } else{
             // Insert event data into the database 
             $sqlQ = "INSERT INTO dentist_list (dentist_name,address,contact_num) VALUES (?,?,?)"; 
             $stmt = $conn->prepare($sqlQ); 
             $stmt->bind_param("sss", $dentist_name, $address, $contact_num); 
             $insert = $stmt->execute(); 
  
             if($insert){ 
                 $output = [ 
                     'status' => 1, 
                     'msg' => 'Record added successfully!' 
                 ]; 
                 echo json_encode($output); 
             }else{ 
                 echo json_encode(['error' => 'Record Add request failed!']); 
             } 
        }
    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
} elseif($jsonObj->request_type == 'deleteDentist'){ 
    $id = $jsonObj->dentist_id; 
    $sql = "DELETE FROM dentist_list WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Record deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Record Delete request failed!']); 
    } 
}
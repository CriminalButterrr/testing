<?php      
// Include database configuration file  
require_once 'dbConnect.php'; 
 
// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 
 
if($jsonObj->request_type == 'addEditTransaction'){ 
    $transaction_data = $jsonObj->transaction_data; 
    $client_name = !empty($transaction_data[0])?$transaction_data[0]:''; 
    $case_nature = !empty($transaction_data[1])?$transaction_data[1]:''; 
    $price = !empty($transaction_data[2])?$transaction_data[2]:''; 
    $id = !empty($transaction_data[3])?$transaction_data[3]:0; 
 
    $err = ''; 
    if(empty($client_name)){ 
        $err .= "Please enter your client's name.<br/>"; 
    } 
    if(empty($case_nature)){ 
        $err .= 'Please enter your the case.<br/>'; 
    } 
    if(empty($price)){ 
        $err .= 'Please enter a price<br/>'; 
    } 
     
    if(!empty($transaction_data) && empty($err)){ 
        if(!empty($id)){ 
            // Update transaction data into the database 
            $sqlQ = "UPDATE transactions SET client_name=?,case_nature=?,price=?,modified=NOW() WHERE id=?"; 
            $stmt = $conn->prepare($sqlQ); 
            $stmt->bind_param("sssi", $client_name, $case_nature, $price, $id); 
            $update = $stmt->execute(); 
 
            if($update){ 
                $output = [ 
                    'status' => 1, 
                    'msg' => 'Transaction updated successfully!' 
                ]; 
                echo json_encode($output); 
            }else{ 
                echo json_encode(['error' => 'Transaction Update request failed!']); 
            } 
           
        } else {
             // Insert event data into the database 
             $sqlQ = "INSERT INTO transactions (client_name,case_nature,price) VALUES (?,?,?)"; 
             $stmt = $conn->prepare($sqlQ); 
             $stmt->bind_param("sss", $client_name, $case_nature, $price); 
             $insert = $stmt->execute(); 
  
             if($insert){ 
                 $output = [ 
                     'status' => 1, 
                     'msg' => 'Transaction added successfully!' 
                 ]; 
                 echo json_encode($output); 
             }else{ 
                 echo json_encode(['error' => 'Transaction Add request failed!']); 
             } 
        } 

    }else{ 
        echo json_encode(['error' => trim($err, '<br/>')]); 
    } 
} elseif($jsonObj->request_type == 'deleteTransaction'){ 
    $id = $jsonObj->transaction_id; 
 
    $sql = "DELETE FROM transactions WHERE id=$id"; 
    $delete = $conn->query($sql); 
    if($delete){ 
        $output = [ 
            'status' => 1, 
            'msg' => 'Transaction deleted successfully!' 
        ]; 
        echo json_encode($output); 
    }else{ 
        echo json_encode(['error' => 'Transaction Delete request failed!']); 
    } 
}
<?php

    $customer_info_data = $_POST['customer_info_data'];   

    

    $first_name = $customer_info_data[0][1];
    $last_name = $customer_info_data[1][1];
    $country = $customer_info_data[2][1];
    $city = $customer_info_data[3][1];
    $postcode = $customer_info_data[4][1];
    $phone = $customer_info_data[5][1];
    $email = $customer_info_data[6][1];
    $street = $customer_info_data[7][1];


    // after storing the data to the database, sending email to Herbert
    // After sending email to Herbert, send email to user

       
    try{

        $user_email_body = '<!DOCTYPE html>
        <html lang="en" dir="ltr">
          <head>
            <meta charset="utf-8" />
          </head>
          <body style="width: 70%; margin-left: 15%">
            <div
              style="
                background-color: #dc3545;
                text-align: center;
                color: white;
                height: 110px;
                padding-top: 10px;
              "
            >
              <h1>A New Order Is Proceeding!</h1>
              <p>Contact to the Customer ASAP!</p>
            </div>
        
            <div class="mt-3" style="background-color: #eeeeee; padding: 4%">
              <div class="row" style="display: flex; flex-wrap: wrap">
              <p style="width: 50%">Customer Name: '.$first_name.' '.$last_name.'</p>
              <p style="width: 50%">Date: '.date("Y/m/d").'</p>
              <p style="width: 50%">Address: '.$street.' '.$city.' '.$country.'</p>
              <p style="width: 50%"></p>
              <p style="width: 50%">Phone#: '.$phone.'</p>
              <p style="width: 50%">Email: '.$email.'</p>
              </div>
              <hr />
        
              <div class="row">
                <div>';         
          
          
        $user_email_footer = '<h2></h2>
                </div>
                <p style="margin-left: 5%; font-weight: bold; margin-left: 1%">
                Herbert | ebenezerroofcontractor@gmail.com | 703-763-6130
                </p>
            </div>
            </div>
        </body>
        </html>';
        $url = 'https://api.elasticemail.com/v2/email/send';
        $filetype = "text/plain"; // Change correspondingly to the file type
        $post = array('from' => 'Ebenezerroofcontractor@gmail.com',
        'fromName' => 'Herbert Fuentes',
        'apikey' => 'A8B639078CB279F83DCC3B79399AA77F82B3A9A1F812E3CF5DEBC80ADF51536A5B2D24F1E2DC6DC66F8314A6C32B5024',
        'subject' => 'Herbert Service',
        'bodyText' => 'Text Body',
        'to' =>'Herbert.f@ebenezercontractors.com',
        'isTransactional' => false,
        );

 
        $user_email_body = $user_email_body.$user_email_footer;
        $post = array_merge($post, array('bodyHtml' => $user_email_body));
        
  
        $ch = curl_init();
        echo($post);
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false
        ));
        
        $result=curl_exec ($ch);
        curl_close ($ch);
        
        echo $result;  
        }
    catch(Exception $ex){
        echo $ex->getMessage();
    }    

?>

<?php

    // $result = $_POST['myData'];
    $customer_wallet = $_POST['customer_wallet'];
    $customer_info = $_POST['customer_info'];
    $customer_info_data = $_POST['customer_info_data'];   
    $payment_method = $_POST['payment_method'];
    
    $warrantied = '';
    $total_price = 0;
    foreach($customer_info as $temp){
        $total_price += (int)$temp[1];
        if($temp[0] == "warranty"){
            if($temp[1] == "75")
                $temp[0] = "warranty (1 month)";
            else if($temp[1] == "125")
                $temp[0] = "warranty (3 months)";
            else
                $temp[0] = "warranty (6 months)";
            $warrantied = $temp[0];
            continue;
        }

        $arr[]   =  $temp[0];
    }

    $scopes = join(",", $arr);

    echo $scopes;
    echo "//";
    echo $warrantied;

    

    $first_name = $customer_info_data[0][1];
    $last_name = $customer_info_data[1][1];
    $country = $customer_info_data[2][1];
    $city = $customer_info_data[3][1];
    $postcode = $customer_info_data[4][1];
    $phone = $customer_info_data[5][1];
    $email = $customer_info_data[6][1];
    $street = $customer_info_data[7][1];
    $wallet = $customer_wallet;
    
// connecting MySQL server

    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "herbert_service";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO service (first_name, last_name, country, city, postcode, phone, email, street, wallet, payment_method, scopes, warrantied, total_price)
    VALUES ('".$first_name."', '".$last_name."', '".$country."','".$city."','".$postcode."','".$phone."', '".$email."', '".$street."','".$wallet."','".$payment_method."','".$scopes."','".$warrantied."','".$total_price."')";

    


    // Sending email to Herbert
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
              <h1>A New Order Is Confirmed!</h1>
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
                <div>
                  <div><h2 style="color: red">SCOPE OF WORK</h2></div><p>';
          $roof_maintenance_txt = '.&nbsp; Roof maintenance :</p>
          -&nbsp;&nbsp;inspect the entire roof for any possible water leaks.
          seal the roof properly; by using exterior caulking, nails or
          shingles.<br />-&nbsp; These proposals include up to 10 shingles to be
          repaired. Exposed nails will be caulked and up to 2 rubber boots to be
          replaced.<br />-&nbsp; The brand, style and color will be affected
          according to the appliance store'."'".'s availability.<br />-&nbsp; Repairs
          are not covered by any sort of warranty or guaranteed to stop a
          leak.&nbsp;<br />
          </p>';
          $window_maintenance_txt = '. Window maintenance :</p>
          <p>
            -&nbsp; A total of 3 windows are included in this proposal, $65 usd
            for every other additional window.<br />-&nbsp; Each window will be
            serviced by applying an exterior window sealant, according to the
            manufacturer'."'".'s specification.<br />-&nbsp; The color of caulking
            does not affect the price, colors or brand can be affected,
            according to the nearest appliance store.<br />
          </p>';
          $gutter_cleaning_txt = '. Gutter Cleaning :</p>
          <p>
            -&nbsp; Clean the entire gutter system, water hose the
            downspouts.<br />-&nbsp;&nbsp;Leaves will be removed from the
            gutters, additional charge for any leaf guard removals.<br />-&nbsp;
            up to 50 linear feet of gutter will be cleaned, anything above 50
            LFT will have an additional charge.<br />-&nbsp; All work related
            material will be hauled away, and leaves will be trashed.<br />
          </p>';
          $window_screen_replacement_txt = '. Window screen replacement :</p>
          <p>
            - This proposal includes 5 window screens to be replaced.<br />-
            Additional windows will be charged at a rate of $85USD per
            screen.<br />-&nbsp; the frame may be reused, according to the
            condition it is in.<br />-&nbsp; frame is included if replacement is
            necessary.&nbsp;&nbsp</br>;
          </p>';
          $aluminum_fasic_txt = '. Aluminum fascia board (CAP) :</p>
          <p>
            - &nbsp;40 linear feet of aluminum cap on the wood fascia boards are
            included in this proposal.<br />- &nbsp;$15 per foot after that.
            color does not determine the price, colors are affected by appliance
            stores.<br />- &nbsp;wood replacement is $8 PER FOOT. pictures and
            reasons are provided.<br />
          </p>';
          $powerwashing_txt = '. Powerwashing :</p>
          <p>
            -&nbsp; Powerwash driveways up to 20’ x10’ and wood decks 20’x
            10’<br />-&nbsp; There will be an additional charge of $50 per SQFT
            after that.<br />-&nbsp; Chemicals will be used according to the
            type of surface being power washed.<br />
          </p>';
        $user_email_footer = '<h2></h2>
        </div>
        <h4 style="color: red">Total Matrial & Labor Cost: $'.$total_price.'</h4>
          <h4 style="color: red">Your Wallet Address: '.$customer_wallet.'</h4>
          <div>
          <table
            style="
              color: rgb(34, 34, 34);
              font-size: small;
              background-color: #eeeeee;
              border: none;
              border-collapse: collapse;
            "
          >
            <colgroup>
              <col width="217" />
              <col width="266" />
            </colgroup>
            <tbody>
              <tr style="height: 181.5pt">
                <td
                  style="
                    font-family: Roboto, RobotoDraft, Helvetica, Arial,
                      sans-serif;
                    margin: 0px;
                    border-right: 2.25pt solid rgb(0, 0, 0);
                    vertical-align: top;
                    padding: 5pt;
                  "
                >
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <a
                      href="https://ebenezerroofingva.com/"
                      target="_blank"
                      data-saferedirecturl="https://www.google.com/url?q=https://ebenezerroofingva.com/&amp;source=gmail&amp;ust=1661503905614000&amp;usg=AOvVaw1hRm_Wa3GDYZH5tQV9ZmI3"
                      ><span
                        style="
                          font-size: 11pt;
                          font-family: Arial;
                          color: rgb(17, 85, 204);
                          background-color: transparent;
                        "
                        ><img
                          src="./src/contract_logo_1.jpg"
                          width="206px"
                          height="205px"
                          style="border: none; visibility: visible"
                          class="CToWUd"
                          data-bit="iit"
                          data-xblocker="passed" /></span
                    ></a>
                  </p>
                </td>
                <td
                  style="
                    font-family: Roboto, RobotoDraft, Helvetica, Arial,
                      sans-serif;
                    margin: 0px;
                    border-left: 2.25pt solid rgb(0, 0, 0);
                    vertical-align: top;
                    padding: 5pt;
                  "
                >
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <span
                      style="
                        font-size: 12pt;
                        font-family: Arial;
                        color: rgb(204, 0, 0);
                        background-color: transparent;
                        font-weight: 700;
                      "
                      >office#:</span
                    ><span
                      style="
                        font-size: 12pt;
                        font-family: Arial;
                        color: rgb(0, 0, 0);
                        background-color: transparent;
                        font-weight: 700;
                      "
                    >
                      (703) 763-6130</span
                    >
                  </p>
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <span
                      style="
                        font-size: 12pt;
                        font-family: Arial;
                        color: rgb(204, 0, 0);
                        background-color: transparent;
                        font-weight: 700;
                      "
                      >sales#:</span
                    ><span
                      style="
                        font-size: 12pt;
                        font-family: Arial;
                        color: rgb(0, 0, 0);
                        background-color: transparent;
                      "
                    >
                    </span
                    ><span
                      style="
                        font-size: 12pt;
                        font-family: Arial;
                        color: rgb(0, 0, 0);
                        background-color: transparent;
                        font-weight: 700;
                        white-space: pre-wrap;
                      "
                      >(703) 577-9388</span
                    >
                  </p>
                  <br />
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <a
                      href="https://ebenezerroofingva.com/"
                      target="_blank"
                      data-saferedirecturl="https://www.google.com/url?q=https://ebenezerroofingva.com/&amp;source=gmail&amp;ust=1661503905614000&amp;usg=AOvVaw1hRm_Wa3GDYZH5tQV9ZmI3"
                      ><span
                        style="
                          font-size: 11pt;
                          font-family: Arial;
                          color: rgb(17, 85, 204);
                          background-color: transparent;
                          white-space: pre-wrap;
                        "
                        ><img
                          src="./src/contract_logo_2.jpg"
                          width="48px"
                          height="48px"
                          style="border: none"
                          class="CToWUd"
                          data-bit="iit" /></span></a
                    ><span
                      style="
                        font-size: 11pt;
                        font-family: Arial;
                        color: rgb(0, 0, 0);
                        background-color: transparent;
                      "
                    >
                      &nbsp;</span
                    ><a
                      href="https://www.yelp.com/biz/ebenezer-roofing-washington?osq=ebenezer+roofing"
                      target="_blank"
                      data-saferedirecturl="https://www.google.com/url?q=https://www.yelp.com/biz/ebenezer-roofing-washington?osq%3Debenezer%2Broofing&amp;source=gmail&amp;ust=1661503905614000&amp;usg=AOvVaw0xurHFWmRxonE2o6mNmOLx"
                      ><span
                        style="
                          font-size: 11pt;
                          font-family: Arial;
                          color: rgb(17, 85, 204);
                          background-color: transparent;
                        "
                        ><img
                          src="./src/contract_logo_3.jpg"
                          width="48px"
                          height="48px"
                          style="border: none"
                          class="CToWUd"
                          data-bit="iit" /></span></a
                    ><span
                      style="
                        font-size: 11pt;
                        font-family: Arial;
                        color: rgb(0, 0, 0);
                        background-color: transparent;
                      "
                    >
                      &nbsp;</span
                    ><a
                      href="https://www.facebook.com/EbenezerRoofingVa/"
                      target="_blank"
                      data-saferedirecturl="https://www.google.com/url?q=https://www.facebook.com/EbenezerRoofingVa/&amp;source=gmail&amp;ust=1661503905614000&amp;usg=AOvVaw3jZntD8DIEnydAf1I5laLr"
                      ><span
                        style="
                          font-size: 11pt;
                          font-family: Arial;
                          color: rgb(17, 85, 204);
                          background-color: transparent;
                        "
                        ><img
                          src="./src//contract_logo_4.jpg"
                          width="55px"
                          height="55px"
                          style="border: none"
                          class="CToWUd"
                          data-bit="iit" /></span
                    ></a>
                  </p>
                  <br />
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <a
                      href="https://www.homeadvisor.com/rated.EbenezerRoofingLLC.50428025.html"
                      target="_blank"
                      data-saferedirecturl="https://www.google.com/url?q=https://www.homeadvisor.com/rated.EbenezerRoofingLLC.50428025.html&amp;source=gmail&amp;ust=1661503905614000&amp;usg=AOvVaw1RS_hRN6fFcYDZ6bdVpWHy"
                      ><span
                        style="
                          font-size: 11pt;
                          font-family: Arial;
                          color: rgb(17, 85, 204);
                          background-color: transparent;
                        "
                        ><img
                          src="./src/contract_logo_5.jpg"
                          width="201px"
                          height="29px"
                          style="border: none"
                          class="CToWUd"
                          data-bit="iit" /></span
                    ></a>
                  </p>
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <a
                      href="https://www.angieslist.com/companylist/us/va/manassas/ebenezer-roofing-reviews-9385042.htm"
                      target="_blank"
                      data-saferedirecturl="https://www.google.com/url?q=https://www.angieslist.com/companylist/us/va/manassas/ebenezer-roofing-reviews-9385042.htm&amp;source=gmail&amp;ust=1661503905614000&amp;usg=AOvVaw1XanDOU-N_qGjBquHIm-A7"
                      ><span
                        style="
                          font-size: 11pt;
                          font-family: Arial;
                          color: rgb(17, 85, 204);
                          background-color: transparent;
                        "
                        ><img
                          src="./src/contract_logo_6.jpg"
                          width="212px"
                          height="65px"
                          style="border: none"
                          class="CToWUd"
                          data-bit="iit" /></span
                    ></a>
                  </p>
                </td>
              </tr>
              <tr style="height: 21pt">
                <td
                  colspan="2"
                  style="
                    font-family: Roboto, RobotoDraft, Helvetica, Arial,
                      sans-serif;
                    margin: 0px;
                    vertical-align: top;
                    padding: 5pt;
                  "
                >
                  <p
                    dir="ltr"
                    style="
                      line-height: 1.2;
                      margin-top: 0pt;
                      margin-bottom: 0pt;
                    "
                  >
                    <span
                      style="
                        font-size: 12pt;
                        font-family: Arial;
                        color: rgb(204, 0, 0);
                        background-color: rgb(217, 217, 217);
                        font-weight: 700;
                        font-style: italic;
                      "
                      >General contractors, specialize in exterior
                      remodeling.</span
                    >
                  </p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <hr />
        <p style="margin-left: 5%; font-weight: bold; margin-left: 1%">
          Herbert | ebenezerroofcontractor@gmail.com | 703-763-6130
        </p>
      </div>
    </div>
  </body>
</html>';
        $url = 'https://api.elasticemail.com/v2/email/send';
        $filename = "helloWorld.txt";
        $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
        $filetype = "text/plain"; // Change correspondingly to the file type
        $post = array('from' => 'Ebenezerroofcontractor@gmail.com',
        'fromName' => 'Herbert Fuentes',
        'apikey' => 'A8B639078CB279F83DCC3B79399AA77F82B3A9A1F812E3CF5DEBC80ADF51536A5B2D24F1E2DC6DC66F8314A6C32B5024',
        'subject' => 'Herbert Service',
        'bodyText' => 'Text Body',
        'to' => $email.';Herbert.f@ebenezercontractors.com',
        'isTransactional' => false,
        );

      $index = 1;       
        foreach($customer_info as $temp){
          if($temp[0] == "warranty"){
            continue;
          }
                
          switch($temp[0]){
            case "window maintenance":
              $user_email_body = $user_email_body.'<span>&#10003;</span>';
              $user_email_body = $user_email_body.$window_maintenance_txt;
              $filename = "windowmaintenance.txt";
              $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
              break;
            case "roof maintenance":
              $user_email_body = $user_email_body.'<span>&#10003;</span>';
              $user_email_body = $user_email_body.$roof_maintenance_txt;
              $filename = "roofmaintenance.txt";
              $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
              break;
            case "gutter cleaning":
              $user_email_body = $user_email_body.'<span>&#10003;</span>';
              $user_email_body = $user_email_body.$gutter_cleaning_txt;
              $filename = "guttercleaning.txt";
              $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
              break;
            case "window screen replace":
              $user_email_body = $user_email_body.'<span>&#10003;</span>';
              $user_email_body = $user_email_body.$window_screen_replacement_txt;
              $filename = "windowscreens.txt";
              $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
              break;
            case "aluminium fasica boards":
              $user_email_body = $user_email_body.'<span>&#10003;</span>';
              $user_email_body = $user_email_body.$aluminum_fasic_txt;
              $filename = "aluminiumfasica.txt";
              $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
              break;
            case "power washing":
              $user_email_body = $user_email_body.'<span>&#10003;</span>';
              $user_email_body = $user_email_body.$powerwashing_txt;
              $filename = "powerwashing.txt";
              $file_name_with_full_path = realpath('./scope_work_txt/'.$filename);
              break;
                      
          }
          $post = array_merge($post, array(("file_".$index) => new CurlFile($file_name_with_full_path, $filetype, $filename)));
        
          $index++;  
        }
        $index = 1;
        $user_email_body = $user_email_body.$user_email_footer;
        $post = array_merge($post, array('bodyHtml' => $user_email_body));
      
        
        $ch = curl_init();
        var_dump($post);
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


// Storing data 

  if ($conn->query($sql) === TRUE) {
      echo "Success";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

    $conn->close();



    

?>

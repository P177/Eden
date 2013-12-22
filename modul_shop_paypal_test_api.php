<?php
	require_once (dirname(__FILE__)."/lang/lang-".$_GET['lang'].".php");
	require_once "./cfg/db.".$_GET['project'].".inc.php";
	require_once "functions.php";
	//session_unset();
	session_start();
	
if ($_GET['action'] == "gtdd"){
	$transactionID = urlencode($_REQUEST['transactionID']);
	
	/* Construct the request string that will be sent to PayPal.
	   The variable $nvpstr contains all the variables and is a
	   name value pair string with & as a delimiter */
	$nvpStr = "&TRANSACTIONID=$transactionID";
	
	/* Make the API call to PayPal, using API signature.
	   The API response is stored in an associative array called $resArray */
	$resArray = HashCall("gettransactionDetails",$nvpStr);
	
	/* Next, collect the API request in the associative array $reqArray
	   as well to display back to the browser.
	   Normally you wouldnt not need to do this, but its shown for testing */
	
	$reqArray = $_SESSION['nvpReqArray'];
	
	/* Display the API response back to the browser.
	   If the response from PayPal was a success, display the response parameters'
	   If the response was an error, display the errors received using APIError.php.
	   */
	$ack = strtoupper($resArray["ACK"]);
	
	if($ack!="SUCCESS"){
		$_SESSION['reshash'] = $resArray;
		$location = $eden_cfg['url_cms']."modul_shop_paypal_test_api.php?action=api_error&lang=".$_GET['lang']."&project=".$_GET['project'];
		header("Location: $location");
		exit;
	}?>
	<html>
	<head>
		<title><?php echo _SHOP_PAYPAL_TEST_TD_TITLE;?></title>
	</head>
	<body>
		<br>
		<center>
		<h2><?php echo _SHOP_PAYPAL_TEST_TD;?></h2>
		<br><br>
		<table width=400>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_PAYER;?></strong></td>
				<td><?php echo $resArray['RECEIVEREMAIL'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_PAYER_ID;?></strong></td>
				<td><?php echo $resArray['PAYERID'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_FIRST_NAME;?></strong></td>
				<td><?php echo $resArray['FIRSTNAME'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_LAST_NAME;?></strong></td>
				<td><?php echo $resArray['LASTNAME'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_TRANS_ID;?></strong></td>
				<td><?php echo $resArray['TRANSACTIONID'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_PARENT_TRANS_ID;?></strong></td>
				<td><?php echo $resArray['PARENTTRANSACTIONID'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_GROSS_AMOUNT;?></strong></td>
				<td><?php echo $resArray['CURRENCYCODE']; echo $resArray['AMT'];?></td>
			</tr>
			<tr>
				<td><strong><?php echo _SHOP_PAYPAL_TEST_TD_PAYMENT_STATUS;?></strong></td>
				<td><?php echo $resArray['PAYERSTATUS'];?></td>
			</tr>
		</table><?php
		$tran_ID = $resArray['TRANSACTIONID'];
		$currency_cd = $resArray['CURRENCYCODE'];
		$gross_amt = $resArray['AMT'];?>		
	</body>
	</html><?php
}
/***********************************************************************************************************
*																											
*		TransactionSearchResults																			
*																											
*		Sends a TransactionSearch NVP API request to PayPal.												
*																											
*		The code retrieves the transaction ID,start date,end date											
*		and constructs the NVP API request string to send to the 											
*		PayPal server. The request to PayPal uses an API Signature.											
*																											
*		After receiving the response from the PayPal server, the											
*		code displays the request and response in the browser. If											
*		the response was a success, it displays the response												
*		parameters. If the response was an error, it displays the											
*		errors received.																					
*																											
*		Called by TransactionSearch.html.																	
*																											
*		Calls CallerService.php and APIError.php.															
*																											
***********************************************************************************************************/
if ($_GET['action'] == "tsr"){
	session_start();
	
	/* Construct the request string that will be sent to PayPal.
	The variable $nvpstr contains all the variables and is a
	name value pair string with & as a delimiter */
	$nvpStr;
	
	$nvpStr = "&STARTDATE=2006-12-26T24:00:00Z";
	
	$nvpStr .= "&INVNUM=".$_GET['iid'];
	
	$resArray = HashCall("TransactionSearch",$nvpStr);
	
	/* Next, collect the API request in the associative array $reqArray
	as well to display back to the browser.
	Normally you wouldnt not need to do this, but its shown for testing */
	
	$reqArray = $_SESSION['nvpReqArray'];
	
	/* Display the API response back to the browser.
	If the response from PayPal was a success, display the response parameters'
	If the response was an error, display the errors received using APIError.php.
	*/
	$ack = strtoupper($resArray["ACK"]);
	
	if($ack != "SUCCESS" && $ack != "SUCCESSWITHWARNING"){
		$_SESSION['reshash'] = $resArray;
		$location = $eden_cfg['url_cms']."modul_shop_paypal_test_api.php?action=api_error&lang=".$_GET['lang']."&project=".$_GET['project'];
		header("Location: $location");
		exit;
	}?>
	<html>
	<head>
		<title><?php echo _SHOP_PAYPAL_TEST_TSR_TITLE;?></title>
	</head>
	<body>
		<br>
		<center>
		<h2><?php echo _SHOP_PAYPAL_TEST_TSR;?></h2>
		<table><?php
		//checking for Transaction ID in NVP response
		if(!isset($resArray["L_TRANSACTIONID0"])){	?>
			<tr>
				<td colspan="5"><?php echo _SHOP_PAYPAL_TEST_TSR_NTS;?></td>
			</tr><?php 
		}else {?>	
			<tr>
				<td width="200"><strong><?php echo _SHOP_PAYPAL_TEST_TSR_ID;?></strong></td>
				<td width="200"><strong><?php echo _SHOP_PAYPAL_TEST_TSR_INVOICE_ID;?></strong></td>
				<td width="200"><strong><?php echo _SHOP_PAYPAL_TEST_TSR_TIME;?></strong></td>
				<td width="100"><strong><?php echo _SHOP_PAYPAL_TEST_TSR_STATUS;?></strong></td>
				<td width="150"><strong><?php echo _SHOP_PAYPAL_TEST_TSR_PAYER_NAME;?></strong></td>
				<td width="80"><strong><?php echo _SHOP_PAYPAL_TEST_TSR_GROSS_AMOUNT;?></strong></td>
			</tr><?php
			$transactionID = $resArray["L_TRANSACTIONID0"];
			$time = $resArray["L_TIMESTAMP0"];
			$timeStamp = FormatPaypalTimestamp($time,"d.m.y - H:i");
			$payerName  = $resArray["L_NAME0"]; 
			$amount  = $resArray["L_AMT0"]; 
			$status  = $resArray["L_STATUS0"]; ?>
			<tr>
				<td valign="top"><a href="modul_shop_paypal_test_api.php?action=gtdd&transactionID=<?php echo $transactionID;?>&amp;lang=<?php echo $_GET['lang'];?>&amp;project=<?php echo $_GET['project'];?>"><?php echo $transactionID;?></a></td>
				<td valign="top"><?php echo $_GET['iid'];?></td>
				<td valign="top"><?php echo $timeStamp."<br />".$resArray["L_TIMEZONE0"];?></td>
				<td valign="top"><?php echo $status;?></td>
				<td valign="top"><?php echo $payerName;?></td>
				<td valign="top" align="right"><?php echo $amount;?></td>
			</tr><?php 
		}//else ?>
	echo "	</table>";
	echo "	</center>";
	echo "</body>";
	echo "</html>";
}
/***********************************************************************************************************
*	APIError																								
*																											
*	Displays error parameters.																				
*																											
*	Called by DoDirectPaymentReceipt.php, TransactionDetails.php,											
*	GetExpressCheckoutDetails.php and DoExpressCheckoutPayment.php.											
*																											
***********************************************************************************************************/
if ($_GET['action'] == "api_error"){
	session_start();
	$resArray = $_SESSION['reshash'];?>
	<html>
	<head>
	<title>PayPal PHP API Response</title>
	<link href="sdk.css" rel="stylesheet" type="text/css"/>
	</head>
	<body alink=#0000FF vlink=#0000FF>
	<center>
		<table width="700">
			<tr>
				<td colspan="2" class="header">The PayPal API has returned an error!</td>
			</tr><?php  //it will print if any URL errors 
		if(isset($_SESSION['curl_error_no'])) { 
				$errorCode= $_SESSION['curl_error_no'] ;
				$errorMessage=$_SESSION['curl_error_msg'] ;	
				session_unset();?>
		   	<tr>
				<td>Error Number:</td>
				<td><?= $errorCode ?></td>
			</tr>
			<tr>
				<td>Error Message:</td>
				<td><?= $errorMessage ?></td>
			</tr>
			
			</center>
		</table><?php 
		} else {
		
		/* If there is no URL Errors, Construct the HTML page with 
	   	Response Error parameters.   
	   	*/?>
	
				<td>Ack:</td>
				<td><?= $resArray['ACK'] ?></td>
			</tr>
			<tr>
				<td>Correlation ID:</td>
				<td><?= $resArray['CORRELATIONID'] ?></td>
			</tr>
			<tr>
				<td>Version:</td>
				<td><?= $resArray['VERSION']?></td>
			</tr><?php
		$count=0;
		while (isset($resArray["L_SHORTMESSAGE".$count])) {		
			  $errorCode    = $resArray["L_ERRORCODE".$count];
			  $shortMessage = $resArray["L_SHORTMESSAGE".$count];
			  $longMessage  = $resArray["L_LONGMESSAGE".$count]; 
			  $count=$count+1;?>
			<tr>
				<td>Error Number:</td>
				<td><?= $errorCode ?></td>
			</tr>
			<tr>
				<td>Short Message:</td>
				<td><?= $shortMessage ?></td>
			</tr>
			<tr>
				<td>Long Message:</td>
				<td><?= $longMessage ?></td>
			</tr><?php 
		}//end while
	}// end else ?>
	echo "	</center>";
	echo "	</table>";
	echo"</body>";
	echo "</html>";
}

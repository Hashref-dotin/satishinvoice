<?php
ob_start();
date_default_timezone_set('Asia/Kolkata');
include 'src.all.inc';
class Invoice
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASS;
    private $database = DB_NAME;
    private $invoiceUserTable = 'invoice_user';
    public $invoiceOrderTable = 'super_invoice_order';
    private $invoiceOrderItemTable = 'super_invoice_order_item';
    private $dbConnect = false;
    public function __construct()
    {
        if (!$this->dbConnect) {
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if ($conn->connect_error) {
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
            }
        }
    }
    private function getData($sqlQuery)
    {
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    private function getNumRows($sqlQuery)
    {
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $numRows = mysqli_num_rows($result);
        return $numRows;
    }
    public function loginUsers($email, $password)
    {
        $sqlQuery = "
			SELECT id, email, first_name, last_name, address, mobile
			FROM " . $this->invoiceUserTable . "
            WHERE email='" . $email . "' AND password='" . md5($password) . "'";
        return $this->getData($sqlQuery);
    }
    public function checkLoggedIn()
    {
        if (!$_SESSION['userid']) {
            header("Location:index.php");
        }
    }

    public function getlastinc($type, $date)
    {
        $invoiceyear =  date("Y", strtotime($date));
        $sqlQuery = "SELECT max(invoice_id) as maxid FROM " . $this->invoiceOrderTable . "
        WHERE datatype='" . $type . "' and year(order_date) = ". $invoiceyear;
        $nextsql = current($this->getData($sqlQuery));
        $curid = (int)$nextsql['maxid'];
        if($curid == 0)
        {
            $curid = substr( $invoiceyear, -2) . '000';
        }
        $nextid = (int)$curid + 1;

        return $nextid;
    }


    public function saveInvoice($POST)
    {
        $invoice_id = $this->getlastinc($POST['datatype'],$POST['order_date']);

        $newDate   =   date("Y-m-d", strtotime($POST['order_date']));
        
        $sqlInsert = "
			INSERT INTO " . $this->invoiceOrderTable . "(user_id, order_receiver_name, order_receiver_address, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, reference, deliverynote, gst, statecode, email, mobile, datatype, declaration, order_date, termstrue, terms,enable_igst, enable_csgst, invoice_id, date_created)
			VALUES ('" . $POST['userId'] . "', '" . addslashes($POST['companyName']) . "', '" . addslashes($POST['address']) . "', '" . (int) $POST['subTotal'] . "', '" . (int) $POST['taxAmount'] . "', '" . (int) $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "', '" . $POST['reference'] . "', '" . $POST['deliverynote'] . "', '" . $POST['gst'] . "', '" . $POST['statecode'] . "', '" . $POST['email'] . "', '" . $POST['mobile'] . "', '" . $POST['datatype'] . "' , '" . $POST['declaration'] . "', '" . $POST['order_date'] . "', ".(int)$POST['termstrue'].", '".addslashes($POST['terms'])."', ".(int)$POST['enable_igst']."  , ".(int)$POST['enable_csgst'].", ".$invoice_id.", '".date("Y-m-d H:i:s")."')";
        if (mysqli_query($this->dbConnect, $sqlInsert)) {
            $lastInsertId = mysqli_insert_id($this->dbConnect);
            for ($i = 0; $i < count($POST['productCode']); $i++) {
                $sqlInsertItem = "
			INSERT INTO " . $this->invoiceOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount,order_item_cgst, order_item_sgst, order_item_igst)
			VALUES ('" . $lastInsertId . "', '" . $POST['productCode'][$i] . "', '" . addslashes($POST['productName'][$i]) . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "', '" . $POST['cgst'][$i] . "', '" . $POST['sgst'][$i] . "', '" . $POST['igst'][$i] . "')";
                mysqli_query($this->dbConnect, $sqlInsertItem);
            } 
            return $lastInsertId;
        } else {
            
            return 'Error in query:'.$sqlInsert ;
        }
    }
    public function updateInvoice($POST)
    {
        $newDate   =   date("Y-m-d", strtotime($POST['order_date']));
    
        if ($POST['invoiceId']) {
            $sqlInsert = "
				UPDATE " . $this->invoiceOrderTable . "
				SET order_receiver_name = '" . addslashes($POST['companyName']) . "', order_receiver_address= '" . addslashes($POST['address']) . "', order_total_before_tax = '" . $POST['subTotal'] . "', order_total_tax = '" . $POST['taxAmount'] . "', order_tax_per = '" . $POST['taxRate'] . "', order_total_after_tax = '" . $POST['totalAftertax'] . "', order_amount_paid = '" . $POST['amountPaid'] . "', order_total_amount_due = '" . $POST['amountDue'] . "', reference = '" . $POST['reference'] . "', deliverynote =  '" . $POST['deliverynote'] . "', gst =  '" . $POST['gst'] . "', mobile =  '" . $POST['mobile'] . "', email =  '" . $POST['email'] . "', statecode =  '" . $POST['statecode'] . "', datatype =  '" . $POST['datatype'] . "', declaration =  '" . addslashes($POST['declaration']) . "', order_date =  '" . $newDate . "', termstrue =  '" . (int)$POST['termstrue'] . "', terms =  '" . addslashes($POST['terms']) . "', enable_igst =  '" . (int)$POST['enable_igst'] . "', enable_csgst =  '" . (int)$POST['enable_csgst'] . "' WHERE user_id = '" . $POST['userId'] . "' AND order_id = '" . $POST['invoiceId'] . "'";
            mysqli_query($this->dbConnect, $sqlInsert);
        }
        $this->deleteInvoiceItems($POST['invoiceId']);
        for ($i = 0; $i < count($POST['productCode']); $i++) {
            $sqlInsertItem = "
				INSERT INTO " . $this->invoiceOrderItemTable . "(order_id, item_code, item_name, order_item_quantity, order_item_price, order_item_final_amount, order_item_cgst, order_item_sgst, order_item_igst)
				VALUES ('" . $POST['invoiceId'] . "', '" . $POST['productCode'][$i] . "', '" . addslashes($POST['productName'][$i]) . "', '" . $POST['quantity'][$i] . "', '" . $POST['price'][$i] . "', '" . $POST['total'][$i] . "', '" . $POST['cgst'][$i] . "', '" . $POST['sgst'][$i] . "', '" . $POST['igst'][$i] . "')";
            mysqli_query($this->dbConnect, $sqlInsertItem);
        }
    }
    public function getInvoiceList($type = '', $search = '', $getcount = false, $offset, $total_records_per_page)
    {

       
        if(!$getcount)
        {
            $sqlQuery = "SELECT * FROM " . $this->invoiceOrderTable . "";

        }
        else
        {
            $sqlQuery = "SELECT count(order_id) as `count` FROM " . $this->invoiceOrderTable . "";

        }
        
        if ($type != "") {
            $sqlQuery .= " WHERE datatype='" . $type . "'";
        }
       
        $sqlQuery .= ' ORDER BY `order_id` desc';
        if(isset($offset) && isset($total_records_per_page))
        {
            $sqlQuery .= ' LIMIT '. (int)$offset .','. (int)$total_records_per_page;
        }
        return $this->getData($sqlQuery);
    }


    public function getInvoice($invoiceId)
    {
        $sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . "
			WHERE user_id = '" . $_SESSION['userid'] . "' AND order_id = '$invoiceId'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        return $row;
    }
    public function getInvoiceItems($invoiceId)
    {
        $sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderItemTable . "
			WHERE order_id = '$invoiceId'";
        return $this->getData($sqlQuery);
    }
    public function deleteInvoiceItems($invoiceId)
    {
        $sqlQuery = "
			DELETE FROM " . $this->invoiceOrderItemTable . "
			WHERE order_id = '" . $invoiceId . "'";
        mysqli_query($this->dbConnect, $sqlQuery);
    }
    public function deleteInvoice($invoiceId)
    {
        $sqlQuery = "
			DELETE FROM " . $this->invoiceOrderTable . "
			WHERE order_id = '" . $invoiceId . "'";
        mysqli_query($this->dbConnect, $sqlQuery);
        $this->deleteInvoiceItems($invoiceId);
        return 1;
    }
}
function currencyformat($amount, $symbol = true)
{
    if ($symbol) {
        setlocale(LC_MONETARY, 'de_DE');
        $money = money_format('%!i', $amount);
        $removedecimal = str_replace('', '', $money);
        return '&#8364; '.$removedecimal;
    } else {
        return (float) $amount;
    }

}
function inWords($number)
{
    $locale = 'en_IN';
    $fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
    $in_words = numfmt_format($fmt, $number);
    return ucwords(strtolower($in_words) . " only.");
}

function invoicenumber($num)
{
    return sprintf('%04d', $num);
}

function checkDateForm($date)
{
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
        return true;
    } else {
        return false;
    }
}
define('WEBPAGE_TITLE', 'Supertechs invoice system');
$supertabactive = 'active highlighttab';
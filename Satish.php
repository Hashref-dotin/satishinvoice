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
    public $invoiceOrderTable = 'satish_invoice_order';
    private $invoiceOrderItemTable = 'satish_invoice_order_item';
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
            die('Error in query: ' . '>' . $sqlQuery . '=>' . mysqli_error());
        }
        $data = array();
        while ($row = mysqli_fetch_array($result)) {
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
        $invoiceyear = date('Y', strtotime($date));
        $sqlQuery = 'SELECT max(invoice_id) as maxid FROM ' . $this->invoiceOrderTable . "
        WHERE datatype='" . $type . "' and year(order_date) = " . $invoiceyear;
        $nextsql = current($this->getData($sqlQuery));
        $curid = (int) $nextsql['maxid'];
        $currentmonth = date('m');

        //decide prefix first
        if ($currentmonth > 3 && $currentmonth < 13) {
            $prefix = date('Y') + 1;
            $prefix = substr($prefix, -2);
        } else if ($currentmonth < 4 && $currentmonth > 0) {
            $prefix = date('Y');
            $prefix = substr($prefix, -2);
        } else {
            $prefix = substr(date('Y'), -2);
        }

        $previd = $prefix . '000'; //22000

        if ($previd > $curid) {
            $curid = $previd;
        }

        $nextid = (int) $curid + 1;

        return $nextid;
    }


    public function saveInvoice($POST)
    {
        $invoice_id = $this->getlastinc($POST['datatype'], $POST['order_date']);

        $newDate   =   date("Y-m-d", strtotime($POST['order_date']));

        $sqlInsert = "
			INSERT INTO " . $this->invoiceOrderTable . "(user_id, order_receiver_name, order_receiver_address, order_total_before_tax, order_total_tax, order_tax_per, order_total_after_tax, order_amount_paid, order_total_amount_due, reference, deliverynote, gst, statecode, email, mobile, datatype, declaration, order_date, termstrue, terms,enable_igst, enable_csgst, invoice_id, date_created)
			VALUES ('" . $POST['userId'] . "', '" . addslashes($POST['companyName']) . "', '" . addslashes($POST['address']) . "', '" . (int) $POST['subTotal'] . "', '" . (int) $POST['taxAmount'] . "', '" . (int) $POST['taxRate'] . "', '" . $POST['totalAftertax'] . "', '" . $POST['amountPaid'] . "', '" . $POST['amountDue'] . "', '" . $POST['reference'] . "', '" . $POST['deliverynote'] . "', '" . $POST['gst'] . "', '" . $POST['statecode'] . "', '" . $POST['email'] . "', '" . $POST['mobile'] . "', '" . $POST['datatype'] . "' , '" . addslashes($POST['declaration']) . "', '" . $POST['order_date'] . "', " . (int)$POST['termstrue'] . ", '" . addslashes($POST['terms']) . "', " . (int)$POST['enable_igst'] . "  , " . (int)$POST['enable_csgst'] . ", " . $invoice_id . ", '" . date("Y-m-d H:i:s") . "')";
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

            return 'Error in query:' . $sqlInsert;
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


        if (!$getcount) {
            $sqlQuery = "SELECT * FROM " . $this->invoiceOrderTable . "";
        } else {
            $sqlQuery = "SELECT count(order_id) as `count` FROM " . $this->invoiceOrderTable . "";
        }

        if ($type != "") {
            $sqlQuery .= " WHERE datatype='" . $type . "'";
        }

        $sqlQuery .= ' ORDER BY `order_id` desc';
        if (isset($offset) && isset($total_records_per_page)) {
            $sqlQuery .= ' LIMIT ' . (int)$offset . ',' . (int)$total_records_per_page;
        }
        return $this->getData($sqlQuery);
    }


    public function getInvoice($invoiceId)
    {
        $sqlQuery = "
			SELECT * FROM " . $this->invoiceOrderTable . "
			WHERE user_id = '" . $_SESSION['userid'] . "' AND order_id = '$invoiceId'";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $row = mysqli_fetch_array($result);
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
    public function searchCompany($keyword)
    {
        if (!empty($keyword)) {
            $sqlQuery = "
        SELECT order_id as id, order_receiver_name as label, order_receiver_name as `value` , order_receiver_address, gst, email, mobile, statecode
        FROM " . $this->invoiceOrderTable . "
        WHERE order_receiver_name like '%" . $keyword . "%' group by order_receiver_name";
            return $this->getData($sqlQuery);
        } else {
            return array();
        }
    }
}
function currencyformat($amount, $symbol = true)
{

    if ($symbol) {
        setlocale(LC_MONETARY, 'en_IN');
        $money = money_format('%!i', $amount);
        $removedecimal = str_replace('.00', '', $money);
        return $removedecimal;
    } else {
        return (int) $amount;
    }
}
function inWords($number)
{
    //A function to convert numbers into Indian readable words with Cores, Lakhs and Thousands.
    $number = (int) $number;
    $words = array(
        '0' => '', '1' => 'one', '2' => 'two', '3' => 'three', '4' => 'four', '5' => 'five',
        '6' => 'six', '7' => 'seven', '8' => 'eight', '9' => 'nine', '10' => 'ten',
        '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen', '14' => 'fourteen', '15' => 'fifteen',
        '16' => 'sixteen', '17' => 'seventeen', '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'fourty', '50' => 'fifty', '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninty'
    );

    //First find the length of the number
    $number_length = strlen($number);
    //Initialize an empty array
    $number_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
    $received_number_array = array();

    //Store all received numbers into an array
    for ($i = 0; $i < $number_length; $i++) {
        $received_number_array[$i] = substr($number, $i, 1);
    }

    //Populate the empty array with the numbers received - most critical operation
    for ($i = 9 - $number_length, $j = 0; $i < 9; $i++, $j++) {
        $number_array[$i] = $received_number_array[$j];
    }

    $number_to_words_string = "";
    //Finding out whether it is teen ? and then multiply by 10, example 17 is seventeen, so if 1 is preceeded with 7 multiply 1 by 10 and add 7 to it.
    for ($i = 0, $j = 1; $i < 9; $i++, $j++) {
        //"01,23,45,6,78"
        //"00,10,06,7,42"
        //"00,01,90,0,00"
        if ($i == 0 || $i == 2 || $i == 4 || $i == 7) {
            if ($number_array[$j] == 0 || $number_array[$i] == "1") {
                $number_array[$j] = intval($number_array[$i]) * 10 + $number_array[$j];
                $number_array[$i] = 0;
            }
        }
    }

    $value = "";
    for ($i = 0; $i < 9; $i++) {
        if ($i == 0 || $i == 2 || $i == 4 || $i == 7) {
            $value = $number_array[$i] * 10;
        } else {
            $value = $number_array[$i];
        }
        if ($value != 0) {
            $number_to_words_string .= $words["$value"] . " ";
        }
        if ($i == 1 && $value != 0) {
            $number_to_words_string .= "Crores ";
        }
        if ($i == 3 && $value != 0) {
            $number_to_words_string .= "Lakhs ";
        }
        if ($i == 5 && $value != 0) {
            $number_to_words_string .= "Thousand ";
        }
        if ($i == 6 && $value != 0) {
            $number_to_words_string .= "Hundred &amp; ";
        }
    }
    if ($number_length > 9) {
        $number_to_words_string = "Sorry This does not support more than 99 Crores";
    }
    return ucwords(strtolower($number_to_words_string) . " Only.");
}

function invoicenumber($num)
{
    return sprintf('%04d', $num);
}

function checkDateForm($date)
{
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
        return true;
    } else {
        return false;
    }
}
define('WEBPAGE_TITLE', 'Satishengineering invoice system');
$satishcompanyabactive = "active highlighttab";

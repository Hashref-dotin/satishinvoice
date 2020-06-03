<?php
class SMS
{
    private $host = 'localhost';
    private $user = 'satish_user';
    private $password = "3RnyX80k";
    private $database = "satish_satish_invoice";
    private $invoiceUserTable = 'invoice_user';
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
    public function getsettings()
    {
        $sqlQuery = "SELECT * FROM invoice_user";
        return current($this->getData($sqlQuery));
    }

    public function getCompanyDetails($table, $id)
    {
        $sqlQuery = "
        SELECT * FROM $table
        WHERE order_id = $id";
        return current($this->getData($sqlQuery));
    }

    public function sendSMS($table, $id)
    {
            $smsData = $this->getsettings();
            $smsMessage = $smsData['smsmessage']; 
            $smsNumbers = explode(',',$smsData['smsusers']); 
            $company = $this->getCompanyDetails($table, $id);
            $smslink = $smsData['link'];
            

            $pricesymbol = array(
                'invoice_order'=>'INR',
                'satish_invoice_order'=>'INR',
                'ssv_invoice_order'=>'INR',
                'super_invoice_order'=>'EURO',
            );

            $invoicetype = array('Invoice' => 'Invoice','Proforma' => 'Proforma invoice', 'Quotation' => 'Quotation');

            setlocale(LC_MONETARY, 'en_IN');
            $money = money_format('%!i', $company['order_total_after_tax']);
            $removedecimal = str_replace('.00', '', $money);
           

            $smsMessage = str_replace('{COMPANY NAME}',$company['order_receiver_name'],$smsMessage);
            $smsMessage = str_replace('{MOBILE}',$company['mobile'],$smsMessage);
            $smsMessage = str_replace('{PRICE}',$pricesymbol[$table] .''.$removedecimal,$smsMessage);
            $smsMessage = str_replace('{INVOICETYPE}',$invoicetype[$company['datatype']],$smsMessage);
            
            $tax = (int)$company['enable_igst'] + (int)$company['enable_csgst']; 

            if($tax > 0)
            {
                $smsMessage = str_replace('{TAX}','+Tax',$smsMessage);
            }
            else
            {
                $smsMessage = str_replace('{TAX}','',$smsMessage);
            }

        $companyname = array(
            'invoice_order'=>'Satish engineering',
            'satish_invoice_order'=>'Satish engineering company',
            'ssv_invoice_order'=>'SSV',
            'super_invoice_order'=>'SupertechS',
        );
           
        $name = $companyname[$table];
        $smsMessage = str_replace('{company}',$name,$smsMessage);
        
        $split = str_split($smsMessage, 20);
        $j=3;
        $text = 'F1=Sir&F2='.urlencode(' ').'&';
        $imp = array();
        foreach($split as $part)
        {
            $imp[]= 'F'.$j.'='.urlencode($part);
            $j++;
        } 
        $text .= implode('&',$imp);

        foreach($smsNumbers as $mobile)
        {
             $smslinksend = str_replace('{MOBILE_NUMBER}', $mobile, $smslink);
             $smslinksend = $smslinksend . $text;
             file_get_contents($smslinksend); 
        }
        return 'SMS sent successfully';
    }

}

<?php 
namespace backend\controllers;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use common\models\UploadRecords;
use common\models\ExcelData;
use common\models\Records;
use common\models\TXNDETAILS;
use common\models\MsmeExcelData;
use yii\web\UploadedFile;
use common\models\BranchMaster;
use common\models\BranchUpload;
use yii\web\Session;
use yii\helpers\ArrayHelper;
// use backend\controllers\SiteController;
// use common\components\GoogleURLShortner ;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
      return [
      'access' => [
      'class' => AccessControl::className(),
      'rules' => [
      [
      'actions' => ['login', 'error','subadmin','process','mfi','mfidetails','mfidet','msmedet','previous','msme','adminprev','msmedetails','updatemfi','both','updatemsme','sendsms','mfisms','adminview','delete','url','report','customerdetails','resetpassword','subaddresetpassword','branch','paymentreport'],
      'allow' => true,
      ],
      [
      'actions' => ['logout', 'index'],
      'allow' => true,
      'roles' => ['@'],
      ],
      ],
      ],
      'verbs' => [
      'class' => VerbFilter::className(),
      'actions' => [
      'logout' => ['post'],
      ],
      ],
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
      return [
      'error' => [
      'class' => 'yii\web\ErrorAction',
      ],
      ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
   /* public function actionIndex()
    {
        return $this->render('home');
      }*/


      public function actionSendsms($id,$type,$mon)
      { 
       $excel = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }
       foreach ($excel as $key => $value) {
        $date=date ('d-m-Y',strtotime($value->DemandDate));
        $Mobile=$value->MobileNo;
        $RecordId=$value->Mid;
        $rec=$value->RecordId;
        $Type=$value->Type;
        $loan=$value->LoanAccountNo;
        $request ="";
        $shorturl = $this->getUrl($rec,$Type,$loan);
        //  var_dump(strlen($Mobile));die();
        if ($Mobile != '') {

           if ( strlen($Mobile) == 10 ) {
             
            $param['send_to'] = $value->MobileNo;
            $param['method']= "sendMessage"; 
            $param['msg'] = "Dear Mr./Mrs/Ms " .$value->ClientName." Your Loan ID ".$value->LoanAccountNo." is due on date ".$date. ".Please go through the url ".$shorturl." visit the branch for payment to avoid the discontinue charge. ";
            $param['userid'] = "2000183786"; $param['password'] ="Afpl@786"; $param['v'] = "1.1"; $param['msg_type'] = "TEXT"; 
            // var_dump($param['msg']);die();
            foreach($param as $key=>$val) { $request.= $key."=".urlencode($val); 
            $request.= "&";

          // var_dump($request) ;die();
            } $request = substr($request, 0, strlen($request)-1); 
            $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?".$request;
            $ch = curl_init($url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $curl_scraped_page = curl_exec($ch);curl_error($ch); curl_close($ch); echo $curl_scraped_page;
            //$result= curl_exec($ch);
      if ($request) {

       
          $value->TinnyUrl=$shorturl;
          $value->SmsStatus=1;
          $value->SmsAlert = strval( $curl_scraped_page);
          $value->save();

          
      } 
    }  
    
      else {
      $value->TinnyUrl='N/A';
    $value->SmsStatus=0;
     $value->SmsAlert = 'Failed';
    $value->save();
      }
          
  }     
   
      else {
      $value->TinnyUrl='N/A';
    $value->SmsStatus=0;
     $value->SmsAlert = 'Failed';
    $value->save();
        
      }
     
      

    }   
     
         
      
    
    
return $this->redirect(['msme','id'=>$rec,'type'=>$type,'mon'=>$mon]);


  }
  public function actionMfisms($id,$type,$mon)
  { 
   $excel = ExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
    $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }
   foreach ($excel as $key => $value) {
    $date=date ('d-m-Y',strtotime($value->DemandDate));
    $Mobile=$value->MobileNo;
    $RecordId = $value->Eid;
    $Type=$value->Type;
     $rec=$value->RecordId;
     $loan=$value->LoanAccountNo;
    $request ="";
    $shorturl = $this->getMfiUrl($rec,$Type,$loan);
    $param['send_to'] = $Mobile;
    if ($Mobile != '') {
     if (strlen($Mobile) == 10) {
       $param['method']= "sendMessage"; 
    $param['msg'] = "Dear Mr./Mrs/Ms "  .$value->ClientName." Your Loan ID ".$value->LoanAccountNo." is due on date ".$date. " .Please go through the link ".$shorturl." visit the branch for payment to avoid the discontinue charge. ";
    $param['userid'] = "2000183786"; $param['password'] ="Afpl@786"; $param['v'] = "1.1"; $param['msg_type'] = "TEXT"; 

    foreach($param as $key=>$val) { $request.= $key."=".urlencode($val); 
    $request.= "&";

      // var_dump($request) ;die();
  } $request = substr($request, 0, strlen($request)-1); 
  $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?".$request;
  $ch = curl_init($url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  $curl_scraped_page = curl_exec($ch); curl_close($ch); echo $curl_scraped_page; 
  if ($request) {
   //var_dump($request);die();
  
  $value->TinnyUrl=$shorturl;
  $value->SmsStatus=1;
  $value->save();
  
}
      
     }
      else {
      $value->TinnyUrl='N/A';
    $value->SmsStatus=0;
    $value->save();
      }
    }
     else {
      $value->TinnyUrl='N/A';
    $value->SmsStatus=0;
    $value->save();
      }
    
}


 return $this->redirect(['mfi','id'=>$rec,'type'=>$type,'mon'=>$mon]);



}



public function actionIndex()
{

 $this->layout='common';
 $model = new UploadRecords();

 if ($model->load(Yii::$app->request->post())) {

  $imagep = UploadedFile::getInstance($model, 'File');
  if($imagep)
  {
   $imageID=$model->imageUpload($imagep);
 }
 else
 {
   $imageID='';
 }



 $model->File=$imageID;
 $model->OnDate=date('Y-m-d H:i:s');
 $model->UploadedBy=Yii::$app->user->identity->UserId;
 if ($model->save()) {


   if ($model->Type == "MFI") {



    $basepath=Yii::getAlias('@storage').'/uploads/';
    $inputFileType='Xlsx';
    $inputFileName=$basepath.$imageID;
    $reader= \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet=$reader->load($inputFileName);
    $sheetcount=$spreadsheet->getSheetCount();

    for ($i = 0; $i < $sheetcount; $i++)
    {
     $sheet = $spreadsheet->getSheet($i);
     $sheetrows = $sheet->getHighestRow();
     for($row = 2; $row<=$sheetrows; $row++)
     {
      $BranchName = (string)$sheet->getCellByColumnAndRow(2,$row);
      $Cluster = (string)$sheet->getCellByColumnAndRow(3,$row);
      $State = (string)$sheet->getCellByColumnAndRow(4,$row);
      $ClientId = (string)$sheet->getCellByColumnAndRow(5,$row);
      $LoanAccountNo = (string)$sheet->getCellByColumnAndRow(6,$row);
      $ClientName = (string)$sheet->getCellByColumnAndRow(7,$row);
      $SpouseName = (string)$sheet->getCellByColumnAndRow(8,$row);
      $VillageName = (string)$sheet->getCellByColumnAndRow(9,$row);
      $Center = (string)$sheet->getCellByColumnAndRow(10,$row);
      $GroupName = (string)$sheet->getCellByColumnAndRow(11,$row);
      $MobileNo = (string)$sheet->getCellByColumnAndRow(12,$row);
      $EmiSrNo = (string)$sheet->getCellByColumnAndRow(13,$row);
      $DemandDate = (string)$sheet->getCellByColumnAndRow(14,$row);
      $LastMonthDue = (string)$sheet->getCellByColumnAndRow(15,$row);
      $CurrentMonthDue = (string)$sheet->getCellByColumnAndRow(16,$row);
      $LatePenalty = (string)$sheet->getCellByColumnAndRow(17,$row);
      $NextInstallmentDate = (string)$sheet->getCellByColumnAndRow(18,$row);
      $UploadMonth = (string)$sheet->getCellByColumnAndRow(19,$row);
      $ProductVertical = (string)$sheet->getCellByColumnAndRow(20,$row);

  if ($DemandDate) {
    $unixdate=($DemandDate-25569)*86400;
    $uni=date("Y-m-d",strtotime(date('Y-m-d',$unixdate)));
  }else{
    $unixdate='';
    $uni='';
  }
  if ($NextInstallmentDate) {
     $Nid=($NextInstallmentDate-25569)*86400;
     $Nidate=date("Y-m-d",strtotime(date('Y-m-d',$Nid)));
  }
  else {
   $Nid='';
   $Nidate='';
  }
    if ( $ProductVertical == 'MFI') {
      $models= New  ExcelData();
      $models->RecordId=$model->RecordId;
      $models->BranchName=$BranchName;
      $models->Cluster=$Cluster;
      $models->State=$State;
      $models->ClientId=$ClientId;
      $models->LoanAccountNo=$LoanAccountNo;
      $models->ClientName=$ClientName;
      $models->SpouseName=$SpouseName;
      $models->VillageName=$VillageName;
      $models->Center=$Center;
      $models->GroupName=$GroupName;
      $models->MobileNo=$MobileNo;
      $models->EmiSrNo=$EmiSrNo;
      $models->DemandDate=$uni;
      $models->LastMonthDue=$LastMonthDue;
      $models->CurrentMonthDue=$CurrentMonthDue;
      $models->LatePenalty=$LatePenalty;
      $models->NextInstallmentDate=$Nidate;
      $models->UploadMonth=$UploadMonth;
      $models->ProductVertical=$ProductVertical;
      $models->OnDate=$model->OnDate;
      $models->Type=$model->Type;
      // echo "<pre>";
      // var_dump($models);die(); echo "</pre>";
      if ($models->save()) {
        $count=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0])->one();
        $mficount = ExcelData::find()->where(['RecordId'=>$model->RecordId,'IsDelete' => 0])->count();
        if ($mficount>0) {
        $count->Count=$mficount;
        if ($count->save()) {
        
         Yii::$app->session->setFlash('success','File Uploaded successfully');
         return $this->redirect(['index']);
        }

        else{
         var_dump($models->getErrors());

       }
        }else{
          Yii::$app->session->setFlash('error','There is some error in file please try again');
        }
     }
     
   }
   else {
       Yii::$app->session->setFlash('error','Due to Wrong format,File can not be uploaded!');
      
    }  
   
    }
    }  //echo "<pre>";
                    //  var_dump($models);echo "</pre>";
                   // die();
    $mismatch=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0])->one();
    $connection = \Yii::$app->db;

    $command = $connection->createCommand("SELECT COUNT(*) as cnt FROM `ExcelData` WHERE `RecordId` = '$model->RecordId' and   `IsDelete`=0  GROUP BY LoanAccountNo HAVING cnt > 1 ");
    $result = $command->queryAll();  
    if ($result) {
      $mismatch->Mismatch=$result[0]['cnt']; 
    } 
    else{
      $mismatch->Mismatch =0 ;
    }

    if ($mismatch->save()) {
      $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from ExcelData WHERE `RecordId`='$model->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
      $result =$mismatchmobile->queryAll();
      $mismatch->MobileCount=$result[0]['cnt'];
      $mismatch->save();
    }

  }
  else{
    $basepath=Yii::getAlias('@storage').'/uploads/';
    $inputFileType='Xlsx';
    $inputFileName=$basepath.$imageID;
    $reader= \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet=$reader->load($inputFileName);
    $sheetcount=$spreadsheet->getSheetCount();

    for ($i = 0; $i < $sheetcount; $i++)
    {
     $sheet = $spreadsheet->getSheet($i);
     $sheetrows = $sheet->getHighestRow();
     for($row = 2; $row<=$sheetrows; $row++)
     {
      $BranchName = (string)$sheet->getCellByColumnAndRow(2,$row);
      $Cluster = (string)$sheet->getCellByColumnAndRow(3,$row);
      $State = (string)$sheet->getCellByColumnAndRow(4,$row);
      $ClientId = (string)$sheet->getCellByColumnAndRow(5,$row);
      $LoanAccountNo = (string)$sheet->getCellByColumnAndRow(6,$row);
      $ClientName = (string)$sheet->getCellByColumnAndRow(7,$row);
      $MobileNo = (string)$sheet->getCellByColumnAndRow(8,$row);
      $EmiSrNo = (string)$sheet->getCellByColumnAndRow(9,$row);
      $DemandDate = (string)$sheet->getCellByColumnAndRow(10,$row);
      $LastMonthDue = (string)$sheet->getCellByColumnAndRow(11,$row);
      $CurrentMonthDue = (string)$sheet->getCellByColumnAndRow(12,$row);
      $LatePenalty = (string)$sheet->getCellByColumnAndRow(13,$row);
      $NextInstallmentDate = (string)$sheet->getCellByColumnAndRow(14,$row);
      $UploadMonth = (string)$sheet->getCellByColumnAndRow(15,$row);
      $ProductVertical = (string)$sheet->getCellByColumnAndRow(16,$row);

     if ($DemandDate) {
    $unixdate=($DemandDate-25569)*86400;
    $uni=date("Y-m-d",strtotime(date('Y-m-d',$unixdate)));
  }else{
    $unixdate='';
    $uni='';
  }
  if ($NextInstallmentDate) {
     $Nid=($NextInstallmentDate-25569)*86400;
     $Nidate=date("Y-m-d",strtotime(date('Y-m-d',$Nid)));
  }
  else {
   $Nid='';
   $Nidate='';
  }

  if ($ProductVertical == 'MSME') {
      $msme= New MsmeExcelData();
      $msme->RecordId=$model->RecordId;
      $msme->BranchName=$BranchName;
      $msme->Cluster=$Cluster;
      $msme->State=$State;
      $msme->ClientId=$ClientId;
      $msme->LoanAccountNo=$LoanAccountNo;
      $msme->ClientName=$ClientName;
      $msme->MobileNo=$MobileNo;
      $msme->EmiSrNo=$EmiSrNo;
      $msme->DemandDate=$uni;
      $msme->LastMonthDue=$LastMonthDue;
      $msme->CurrentMonthDue=$CurrentMonthDue;
      $msme->LatePenalty=$LatePenalty;
      $msme->NextInstallmentDate=$Nidate;
      $msme->UploadMonth=$UploadMonth;
      $msme->ProductVertical=$ProductVertical;
      $msme->OnDate=$model->OnDate;
      $msme->Type=$model->Type;
// echo"<pre>";
//       var_dump($msme);echo"</pre>";die();
      if ($msme->save()) {
        $count=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0])->one();
        $msmecount = MsmeExcelData::find()->where(['RecordId'=>$model->RecordId,'IsDelete' => 0])->count();
        $count->Count=$msmecount;
        // var_dump($msmecount);
        if ($count->save()) {
         Yii::$app->session->setFlash('success','File Uploaded successfully');
       }
       else{
         var_dump($msme->getErrors());
       }

     }
  }
    else {
       Yii::$app->session->setFlash('error','Due to Wrong format,File can not be uploaded!');
      
    }  


   }
 }
 $mismatch=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0])->one();
 $connection = \Yii::$app->db;

 $command = $connection->createCommand("SELECT COUNT(*) as cnt FROM `MsmeExcelData` WHERE `RecordId` = '$model->RecordId' and `IsDelete`= 0 GROUP BY LoanAccountNo HAVING cnt > 1 ");
 $result = $command->queryAll();   
        // var_dump($result);die();
 if ($result) {
   $mismatch->Mismatch=$result[0]['cnt']; 
 }
 else{
  $mismatch->Mismatch=0 ;
}

if ($mismatch->save()) {
  $connection = \Yii::$app->db;

  $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from MsmeExcelData WHERE `RecordId`='$model->RecordId' and `IsDelete`=0 and length(`MobileNo`) != 10");
  $result =$mismatchmobile->queryAll();
  $mismatch->MobileCount=$result[0]['cnt'];
  $mismatch->save();

}

}
}

else{
 var_dump($model->getErrors());
}



}
$month=UploadRecords::find()->where(['UploadedBy'=>Yii::$app->user->identity->UserId,'IsApproved'=>1,'IsDelete'=>0])->all();
$currentYear=date('Y');

// if($month){
//  $MonthYear = array( 
//   "Jan/$currentYear" => "January, $currentYear", "Feb/$currentYear" => "February, $currentYear", "Mar/$currentYear" => "March, $currentYear", "Apr/$currentYear" => "April, $currentYear",
//   "May/$currentYear" => "May, $currentYear", "Jun/$currentYear" => "June, $currentYear", "Jul/$currentYear" => "July, $currentYear", "Aug/$currentYear" => "August, $currentYear",
//   "Sept/$currentYear" => "September, $currentYear", "Oct/$currentYear" => "October, $currentYear", "Nov/$currentYear" => "November, $currentYear", "Dec/$currentYear" => "December, $currentYear",
//   );
//  foreach ($month as $key => $val) {
//   unset($MonthYear[$val->MonthYear]);
// }
// }
// else{
//echo date('m');
  $MonthYear = array( 
    "Jan/$currentYear" => "January, $currentYear", "Feb/$currentYear" => "February, $currentYear", "Mar/$currentYear" => "March, $currentYear", "Apr/$currentYear" => "April, $currentYear",
    "May/$currentYear" => "May, $currentYear", "Jun/$currentYear" => "June, $currentYear", "Jul/$currentYear" => "July, $currentYear", "Aug/$currentYear" => "August, $currentYear",
    "Sept/$currentYear" => "September, $currentYear", "Oct/$currentYear" => "October, $currentYear", "Nov/$currentYear" => "November, $currentYear", "Dec/$currentYear" => "December,$currentYear",);
$MonthYear=array_slice($MonthYear, (date('m')-1)); 
  $typee=Yii::$app->user->identity->Type;
  $uid=Yii::$app->user->identity->UserId;

  if ($typee !="both") {

   $details = UploadRecords::find()->where(['UploadedBy'=>$uid,'Type'=>$typee,'IsDelete' => 0])->andWhere(['!=','Count','0'])->orderBy(['RecordId'=>SORT_DESC])->one();

 }
  $details = UploadRecords::find()->where(['UploadedBy'=>$uid,'IsDelete' => 0])->andWhere(['!=','Count','0'])->orderBy(['RecordId'=>SORT_DESC])->one();
  return $this->render('home', ['model' => $model, 'MonthYear' => $MonthYear, 'details' => $details]);

}

public function actionBoth($id,$type,$mon){

  $uid=Yii::$app->user->identity->UserId;
  $role=Yii::$app->user->identity->Role;

  if($role==1)
    $types=UploadRecords::find()->where(['IsDelete' => 0,'RecordId'=>$id])->one();
  else
    $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'IsDelete' => 0,'RecordId'=>$id])->one();


  if ($types->Type == "MFI") {

    return $this->redirect(['mfi', 'id' => $types->RecordId, 'type'=>$type,'mon'=>$types->MonthYear]);
  }
  else
  {
    return $this->redirect(['msme','id'=>$types->RecordId, 'type'=>$type,'mon'=>$types->MonthYear]);
  }

}

public function actionMfi($id,$type,$mon)
{   
 $this->layout = 'common';
 list($month, $year) =explode("/",$mon);
 $month = date("m",strtotime($month));  
 $getid=array();
 $details = ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])->all();
 $approve= ExcelData::find()->where(['RecordId'=>$id,'IsApproved'=>1,'IsDelete'=>0])->count();   
 $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
 foreach ($upload as $key => $value) {
 $mon=$value->MonthYear;
 }
 $command = Yii::$app->db->createCommand("SELECT Eid FROM ExcelData WHERE LoanAccountNo IN (SELECT LoanAccountNo  FROM ExcelData WHERE RecordId='$id'AND IsDelete = 0 GROUP BY LoanAccountNo HAVING COUNT(LoanAccountNo) > 1) and RecordId = '$id'");
 $getallid = $command->queryAll();
 foreach ($getallid as $key => $value) {
    //var_dump($value);
   $getid[]= $value['Eid'];
 }
 foreach ($details as $key => $value) {
  $sms=$value->SmsStatus;
   $paymetdetails = TXNDETAILS::find()->select('SUM(TXN_AMT) as TXN_AMT,TXN_STATUS' )->where(['USER_ID'=>$value->Eid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1,'TYPE'=>'MFI'])->one();
 }
 return $this->render('mfi',['details'=>$details, 'id'=>$id, 'approve'=>$approve,'getid'=>$getid,'sms'=>$sms,'type'=>$type,'paymetdetails'=>$paymetdetails,'mon'=>$mon]);


}
public function actionMfidetails($id,$type,$mon)
{   
 $this->layout = 'common';
  $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }

 if (yii::$app->request->post()) {
   $update = UploadRecords::updateAll(['IsApproved'=>1],['RecordId'=>$id]);

  if ( $update) {
    $mfiupdate = ExcelData::updateAll(['IsApproved'=>1],['RecordId'=>$id]);
  }
   
    if ($mfiupdate) {
      $details = ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])->all();
      
      return $this->render('mfidetails',['details'=>$details,'type'=>$type,'mon'=>$mon]);
    }
  
}

}

public function actionMsme($id,$type,$mon)
{   
  $this->layout = 'common';
    list($month, $year) =explode("/",$mon);
$month = date("m",strtotime($month));
  $getid=array();

  $details = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])->all();
  $approve= MsmeExcelData::find()->where(['RecordId'=>$id,'IsApproved'=>1,'IsDelete'=>0])->count();
   $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
   foreach ($upload as $key => $value) {
   $mon=$value->MonthYear;
   }
  $command = Yii::$app->db->createCommand("SELECT Mid FROM MsmeExcelData WHERE LoanAccountNo IN (SELECT LoanAccountNo  FROM MsmeExcelData WHERE RecordId='$id'AND IsDelete = 0 GROUP BY LoanAccountNo HAVING COUNT(LoanAccountNo) > 1) and RecordId = '$id'");
  $getallid = $command->queryAll();

  $getblank = Yii::$app->db->createCommand("SELECT ( a.a1 + a.a2 + a.a3 + a.a4 + a.a5 + a.a6 + a.a7 + a.a8 + a.a9 + a.a10 + a.a11 + a.a12 + a.a13 + a.a14 + a.a15 ) AS blankcount FROM ( SELECT SUM(CASE WHEN BranchName ='' THEN 1 ELSE 0 END )as a1,SUM(CASE WHEN Cluster ='' THEN 1 ELSE 0 END )as a2, SUM(CASE WHEN State ='' THEN 1 ELSE 0 END )as a3, SUM(CASE WHEN ClientId ='' THEN 1 ELSE 0 END )as a4, SUM(CASE WHEN LoanAccountNo ='' THEN 1 ELSE 0 END )as a5 , SUM(CASE WHEN ClientName ='' THEN 1 ELSE 0 END )as a6, SUM(CASE WHEN MobileNo ='' THEN 1 ELSE 0 END )as a7 , SUM(CASE WHEN EmiSrNo ='' THEN 1 ELSE 0 END )as a8, SUM(CASE WHEN DemandDate ='' THEN 1 ELSE 0 END )as a9, SUM(CASE WHEN LastMonthDue ='' THEN 1 ELSE 0 END )as a10, SUM(CASE WHEN CurrentMonthDue ='' THEN 1 ELSE 0 END )as a11, SUM(CASE WHEN LatePenalty ='' THEN 1 ELSE 0 END )as a12, SUM(CASE WHEN NextInstallmentDate ='' THEN 1 ELSE 0 END )as a13, SUM(CASE WHEN UploadMonth ='' THEN 1 ELSE 0 END )as a14, SUM(CASE WHEN ProductVertical ='' THEN 1 ELSE 0 END )as a15  from MsmeExcelData a WHERE RecordId= $id ) a");
  $getblankvalue = $getblank->queryScalar();
 
  foreach ($getallid as $key => $value) {
    //var_dump($value);
   $getid[]= $value['Mid'];
 }
 foreach ($details as $key => $value) {
  $sms=$value->SmsStatus;
   $paymetdetails = TXNDETAILS::find()->select('SUM(TXN_AMT) as TXN_AMT,TXN_STATUS' )->where(['USER_ID'=>$value->Mid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1,'Type'=>'MSME'])->one();
 }
 
 
 return $this->render('msme',['details'=>$details,'id'=>$id,'approve'=>$approve,'getid'=>$getid,'sms'=>$sms,'getblankvalue'=>$getblankvalue,'type'=>$type,'paymetdetails'=>$paymetdetails,'mon'=>$mon]);

}
public function actionMsmedetails($id,$type,$mon)
{   
  $this->layout = 'common';
     $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }

  if (Yii::$app->request->post()) {
    $update= UploadRecords::updateAll(['IsApproved'=>1],['RecordId'=>$id]);

    if ($update) {
     $msmeupdate= MsmeExcelData::updateAll(['IsApproved'=>1],['RecordId'=>$id]);
     if ($msmeupdate) {
       $details = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])->all();
       return $this->render('msmedetails',['details'=>$details,'RecordId'=>$id,'type'=>$type,'mon'=>$mon]);
     }
   }

 }

}
public function actionUpdatemfi($id,$type){
  $this->layout = 'common';
  $model = ExcelData::find()->where(['Eid'=> $id,'IsDelete'=>0])->one();
  $details = $model->RecordId;
  if ($model->load(Yii::$app->request->post()) && $model->save()) {
   $uid=Yii::$app->user->identity->UserId;
   $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();
        //$mismatch=UploadRecords::find()->where(['RecordId'=>$types->RecordId,'IsDelete'=>0])->one();
   $connection = \Yii::$app->db;
   $command = $connection->createCommand("SELECT COUNT(*) as cnt FROM `ExcelData` WHERE `RecordId` = '$types->RecordId' and `IsDelete`= 0 GROUP BY LoanAccountNo HAVING cnt > 1 ");
   $result = $command->queryAll(); 
          
   if ($result) {
     $types->Mismatch=$result[0]['cnt']; 
   }
   else{
    $types->Mismatch=0 ;
  }
  if ($types->save()) {
    $connection = \Yii::$app->db;
    $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from ExcelData WHERE `RecordId`='$types->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
    $result =$mismatchmobile->queryAll();
    $types->MobileCount=$result[0]['cnt'];
    if ($types->save()){
      Yii::$app->session->setFlash('success', "Account Updated successfully");
      return $this->redirect(['mfi', 'id' => $types->RecordId,'type'=>$type]);
    }
  }

}
return $this->render('mfi_edit',['model'=>$model,'details'=>$details,'type'=>$type]); 

}
public function actionUpdatemsme($id,$type){
  $this->layout='common';
  $model= MsmeExcelData::find()->where(['Mid'=>$id,'IsDelete'=>0])->one();
  $details=$model->RecordId;
  if ($model->load(Yii::$app->request->post()) && $model->save()) {
    $uid=Yii::$app->user->identity->UserId;
    $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();
    $connection=\Yii::$app->db;
    $command=$connection->createCommand("SELECT COUNT(*) as cnt FROM `MsmeExcelData` WHERE `RecordId` = '$types->RecordId' and `IsDelete`= 0 GROUP BY LoanAccountNo HAVING cnt > 1 ");
    $result = $command->queryAll();
    if ($result) {
      $types->Mismatch=$result[0]['cnt'];
    }
    else{
      $types->Mismatch=0;
    }
    if ($types->save()) {
      $connection = \Yii::$app->db;
      $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from `MsmeExcelData` WHERE `RecordId`='$types->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
      $result =$mismatchmobile->queryAll();
      $types->MobileCount=$result[0]['cnt'];
    }
    if ($types->save()) {
     Yii::$app->session->setFlash('success', "Account Updated successfully");
     return $this->redirect(['msme', 'id' => $types->RecordId,'type'=>$type]);
   }
 }
 return $this->render('msme_edit',['model'=>$model,'details'=>$details,'type'=>$type]);
}

public function actionUploaded()
{
 $this->layout = 'common';
 return $this->render('uploaded_view');
}
private function getUrl($RecordId,$Type,$loan){
      //$excel = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
  $link = urlencode('http://128.199.184.65/onlineportal/site/login?id='.$RecordId.'&typ='.$Type.'&l='.$loan);
  $key='91a58dbc5e02d2897e9c6eb5d9466f3d2ca17';
  $url='https://cutt.ly/api/api.php';
  $json = file_get_contents($url."?key=$key&short=$link"); 
  $data=json_decode($json,true);

  return $data["url"]['shortLink']; 
}
private function getMfiUrl($RecordId,$Type,$loan){
 // $excel = ExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->one();


 $link = urlencode('http://128.199.184.65/onlineportal/site/login?id='.$RecordId.'&typ='.$Type.'&l='.$loan);
 $key='91a58dbc5e02d2897e9c6eb5d9466f3d2ca17';
 $url='https://cutt.ly/api/api.php';
 $json = file_get_contents($url."?key=$key&short=$link"); 
 $data=json_decode($json,true);
 return $data["url"]["shortLink"];              
}

public function actionPayment()
{
 $this->layout= 'common';
 return $this->render('payment_view');
}
public function actionRecord()
{
 $this->layout= 'common';
 return $this->render('record_details');
}
public function actionProcess()
{
 $this->layout = 'common';
 $records= Records::find()->where(['IsDelete' => 0])->all(); 
 return $this->render('process',['records'=>$records]);
}
public function actionUpdate()
{
 $this->layout = 'common';
 return $this->render('record_update');
}
public function actionPrevious()
{
 $this->layout = 'common';
        // $typee= Yii::$app->user->identity->Type;
 $uid=Yii::$app->user->identity->UserId;
 $details = UploadRecords::find()->where(['UploadedBy'=>$uid ,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->all();

//  foreach ($details as $key => $value) {
//    $approve=$value->IsApproved;
//  }

 return $this->render('previous_record_view',['details'=>$details]);

}
public function actionAdminprev(){
   $this->layout = 'common';
   $details = UploadRecords::find()->where(['UploadedBy'=>1,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->all();
return $this->render('admin_view',['details'=>$details]);


}
public function actionAdminview()
{
 $this->layout = 'common';
        // $typee= Yii::$app->user->identity->Type;
 $uid=Yii::$app->user->identity->UserId;
 $details = UploadRecords::find()->where(['IsDelete' => 0,'IsApproved'=>1])->andWhere(['!=','UploadedBy','1'])->orderBy(['RecordId'=>SORT_DESC])->all();


 return $this->render('previous_record_view',['details'=>$details]);

}
public function actionDelete($id)
{   


  $model=UploadRecords::find()->where(['RecordId'=>$id])->one();
  $model->IsDelete=1;
  if($model->save()){

   if($model->Type == 'MFI'){
    $update = ExcelData::updateAll(['IsDelete'=>1],['RecordId'=>$id]);  
    if ($update) {
      Yii::$app->session->setFlash('success', "Account deleted successfully");
    }

  }
  elseif($model->Type == 'MSME'){
   $update = MsmeExcelData::updateAll(['IsDelete'=>1],['RecordId'=>$id]);   
   if ($update) {
    Yii::$app->session->setFlash('success', "Account deleted successfully");
  }

}
else{

                //var_dump($model->getErrors());die();
 Yii::$app->session->setFlash('error', "There is some error!");
}
}
return $this->redirect(['index']);
}
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
      if (!Yii::$app->user->isGuest) {
        return $this->goHome();
      }

      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
        return $this->goBack();
      } else {
        $model->Password = '';

        return $this->render('login', [
          'model' => $model,
          ]);
      }
    }

public function actionPaymentreport(){
       $this->layout = 'common';
      $getpayment=Yii::$app->db->createCommand("SELECT tb.RecordId, tb.BranchName,tb.Cluster,tb.State,tb.ClientId,tb.LoanAccountNo,tb.ClientName,tb.MobileNo,tb.EmiSrNo,tb.DemandDate,tb.LastMonthDue,tb.CurrentMonthDue,tb.LatePenalty,tb.NextInstallmentDate,tb.UploadMonth,tb.ProductVertical,txn.MONTH as txnmonth,txn.TXN_DATE as txndate,txn.TXN_AMT as txnamnt,txn.TXN_STATUS FROM MsmeExcelData tb LEFT JOIN TXN_DETAILS txn ON tb.Mid = txn.USER_ID UNION ALL SELECT tbex.RecordId, tbex.BranchName,tbex.Cluster,tbex.State,tbex.ClientId,tbex.LoanAccountNo,tbex.ClientName,tbex.MobileNo,tbex.EmiSrNo,tbex.DemandDate,tbex.LastMonthDue,tbex.CurrentMonthDue,tbex.LatePenalty,tbex.NextInstallmentDate,tbex.UploadMonth,tbex.ProductVertical,txnn.MONTH as txnmonth,txnn.TXN_DATE as txndate,txnn.TXN_AMT as txnamnt,txnn.TXN_STATUS FROM ExcelData tbex LEFT JOIN TXN_DETAILS txnn ON tbex.Eid = txnn.USER_ID");
      $getallreport =$getpayment->queryAll();

      return $this->render('paymentreport',['getallreport'=>$getallreport]);

    }
   

    public function actionReport()
        { 
          $model= new MsmeExcelData();
          $this->layout = 'common';
          $zonename=$branchname=$product=$type='';
          $fromdate=date('Y-m-01');
          $todate=date("Y-m-d");
          $allcustomer=$alldetails=array();
          $branchnnm=ArrayHelper::map(BranchMaster::find()->where(['IsDelete'=>0])->all(),'BranchName','BranchName');
          /*$brnchnm=Yii::$app->db->createCommand("SELECT distinct tmp.BranchName FROM ( (select Distinct BranchName as BranchName from MsmeExcelData WHERE BranchName !='') UNION (select Distinct BranchName as BranchName from ExcelData WHERE BranchName !='') ) as tmp");
            $branchnnm =$brnchnm->queryAll();*/

            $getcustom=Yii::$app->db->createCommand("
               SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)
              
              ");
            $allcustomer =$getcustom->queryAll();
          
          //$branchnnm=MsmeExcelData::find()->select('BranchName')->distinct()->where(['IsDelete'=>0])->all();
          if (isset(Yii::$app->request->post()['report_search'])) {
            $type=Yii::$app->request->post()['type']?Yii::$app->request->post()['type']:'';
            $branchname=Yii::$app->request->post()['MsmeExcelData']['BranchName']?Yii::$app->request->post()['MsmeExcelData']['BranchName']:'';
            $fromdt=Yii::$app->request->post()['fromdate']?Yii::$app->request->post()['fromdate']:'';
            $todt=Yii::$app->request->post()['todate']?Yii::$app->request->post()['todate']:'';
            $fromdate=date('Y-m-d', strtotime('$fromdt'));
            $todate=date('Y-m-d', strtotime('$todt'));

           if ($type !='' && $branchname =='' && $fromdate == '' && $todate =='') {
                /*if ($type == 'MSME') {
                  $where= 'ms.ProductVertical LIKE '. $type;

                }else{
                   $where= 'es.ProductVertical LIKE '.$type;
                }*/

                 $getcustom=Yii::$app->db->createCommand("SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.ProductVertical LIKE '$type' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.ProductVertical LIKE '$type' GROUP BY month(es.DemandDate)");
                
                
           } else if ($type =='' && $branchname !='' && $fromdate == '' && $todate =='') {
             $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.BranchName LIKE '$branchname' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.BranchName LIKE '$branchname' GROUP BY month(es.DemandDate)");
           }else if ($type =='' && $branchname =='' && $fromdate != '' && $todate !='') {
             $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID  AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)");

           }else if ($type !='' && $branchname !='' && $fromdate == '' && $todate =='') {
             $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.ProductVertical LIKE '$type' AND  ms.BranchName LIKE '$branchname' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.ProductVertical LIKE '$type' AND  es.BranchName LIKE '$branchname' GROUP BY month(es.DemandDate)");
           }else if ($type !='' && $branchname =='' && $fromdate != '' && $todate !='') {
             $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.ProductVertical LIKE '$type' AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.ProductVertical LIKE '$type' AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)");
           }else if ($type =='' && $branchname !='' && $fromdate != '' && $todate !='') {
             $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.BranchName LIKE '$branchname'  AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.BranchName LIKE '$branchname' AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)");
           }else if ($type !='' && $branchname !='' && $fromdate != '' && $todate !=''){
            $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.BranchName LIKE '$branchname' AND ms.ProductVertical LIKE '$type' AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.BranchName LIKE '$branchname' AND es.ProductVertical LIKE '$type' AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)");
           }else{
            $getcustom=Yii::$app->db->createCommand("
               SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID GROUP BY month(es.DemandDate)
              
              ");
           }
            /*if ($type == 'MSME') {
              $getcustom=Yii::$app->db->createCommand("SELECT txn.*, ms.State as state,ms.DemandDate,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.BranchName LIKE '$branchname'  AND ms.ProductVertical LIKE '$type' AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)");
             
            }
            else if ($type == 'MFI') {
              $getcustom=Yii::$app->db->createCommand("SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.BranchName LIKE '$branchname' AND es.ProductVertical LIKE '$type' AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)");
            }else{
              $getcustom=Yii::$app->db->createCommand("
                SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.BranchName LIKE '$branchname' AND ms.ProductVertical LIKE '$type' AND ms.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.BranchName LIKE '$branchname' AND es.ProductVertical LIKE '$type' AND es.DemandDate BETWEEN '$fromdate' AND '$todate' GROUP BY month(es.DemandDate)");
            }*/
           
           /* $getcustom=Yii::$app->db->createCommand("
            SELECT txn.*, ms.State as state,ms.Cluster as cluster,ms.BranchName as branchname,ms.type as type,ms.DemandDate as ddate,month(ms.DemandDate) as month,year(ms.DemandDate) as yr,ms.CurrentMonthDue,ms.LatePenalty,(ms.CurrentMonthDue+ms.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as collectedamount,(SELECT COUNT(Mid) from MsmeExcelData ) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=ms.LoanAccountNo) as paidcustomer FROM MsmeExcelData ms,TXN_DETAILS txn WHERE ms.Mid = txn.USER_ID AND ms.BranchName LIKE '$branchname' AND ms.ProductVertical LIKE '$type' AND ms.DemandDatee BETWEEN $fromdate AND $todate GROUP BY month(ms.DemandDate)
            UNION ALL
            SELECT txn.*, es.State as state,es.Cluster as cluster,es.BranchName as branchname,es.type as type,es.DemandDate as ddate,month(es.DemandDate) as month,year(es.DemandDate) as yr,es.CurrentMonthDue,es.LatePenalty,(es.CurrentMonthDue+es.LatePenalty) as totalamountdue,(SELECT SUM(TXN_AMT) FROM TXN_DETAILS WHERE TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as collectedamount,(SELECT COUNT(Eid) from ExcelData) as customer,(SELECT COUNT(DISTINCT USER_ID) FROM TXN_DETAILS WHERE txn.TXN_STATUS=1 and LOAN_ID=es.LoanAccountNo) as paidcustomer FROM ExcelData es,TXN_DETAILS txn WHERE es.Eid = txn.USER_ID AND es.BranchName LIKE '$branchname' AND es.ProductVertical LIKE '$type' AND es.DemandDate BETWEEN $fromdate AND $todate GROUP BY month(es.DemandDate)
              ");*/
            $allcustomer =$getcustom->queryAll();
        }
         return $this->render('report', [
          'model'=>$model,'allcustomer' => $allcustomer,'branchnnm'=>$branchnnm,'branchname'=>$branchname,'type'=>$type,'fromdate'=>$fromdate,'todate'=>$todate
          ]);
      }

    /**
     * Logout action.
     *
     * @return string
     */

    public function actionCustomerdetails($date,$type)
      {   
       $this->layout = 'common';
       $month = date('m', strtotime($date));
       $year = date('Y', strtotime($date));
       if ($type == 'MSME') {
         $details = MsmeExcelData::find()->where(['month(DemandDate)'=>$month,'year(DemandDate)' => $year,'IsDelete' => 0])->all();
         if ($details) {
          foreach ($details as $key => $val) {
            $paymetdetails = TXNDETAILS::find()->select('SUM(TXN_AMT) as TXN_AMT' )->where(['USER_ID'=>$val->Mid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1])->one();
            $txnsts = TXNDETAILS::find()->where(['USER_ID'=>$val->Mid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1])->one();
          }
        }
       }else{
        $details = ExcelData::find()->where(['month(DemandDate)'=>$month,'year(DemandDate)' => $year,'IsDelete' => 0])->all();
         if ($details) {
          foreach ($details as $key => $val) {
            $paymetdetails = TXNDETAILS::find()->select('SUM(TXN_AMT) as TXN_AMT')->where(['USER_ID'=>$val->Eid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1])->one();
            $txnsts = TXNDETAILS::find()->where(['USER_ID'=>$val->Eid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1])->one();
          }
        }
       }
       
       /*$details = ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])->all();
       $approve= ExcelData::find()->where(['RecordId'=>$id,'IsApproved'=>1,'IsDelete'=>0])->count(); */  
       
       return $this->render('customerdetails',['details'=>$details,'type'=>$type,'paymetdetails'=>$paymetdetails,'txnsts'=>$txnsts]);


      }
      
    public function actionSubaddresetpassword(){
      $this->layout = 'common';
      $subadmin= User::find()->where(['Role'=> 2])->all();

      if(yii::$app->request->post()){
      $subadd = yii::$app->request->post('subadd');
      $npassword = yii::$app->request->post('npsw');
      $cpassword = yii::$app->request->post('cpsw');
      if ($npassword == $cpassword) {
        $user=User::find()->where(['UserId'=>$subadd])->one();

        $user->Password=Yii::$app->getSecurity()->generatePasswordHash($npassword);
        
        if($user->save())
        {
          Yii::$app->session->setFlash('success', "Password reset Success");
          return $this->redirect(['subaddresetpassword']);
        }
        else{
          var_dump($user->getErrors());die();
          Yii::$app->session->setFlash('error', "Somthing went wrong,please try again!");
          // return $this->redirect(['resetpassword']);
        }
      }
      else{
        Yii::$app->session->setFlash('error', "New Password and Confirm Password does not match!");
        return $this->redirect(['subaddresetpassword']);
      }
    }

      return $this->render('subaddresetpassword',['subadmin'=>$subadmin]);
    }

    public function actionResetpassword()
    {
      $this->layout = 'common';
      $userid=Yii::$app->user->identity->UserId;
      if(yii::$app->request->post()){
      $npassword = yii::$app->request->post('npsw');
      $cpassword = yii::$app->request->post('cpsw');
      if ($npassword == $cpassword) {
        $user=User::find()->where(['UserId'=>$userid])->one();

        $user->Password=Yii::$app->getSecurity()->generatePasswordHash($npassword);
        if(Yii::$app->user->identity->Role == 1){
          $user->Type= "admin";
        }
        
        if($user->save())
        {
          Yii::$app->session->setFlash('success', "Password reset Success");
          return $this->redirect(['resetpassword']);
        }
        else{
          //var_dump($user->getErrors());die();
          Yii::$app->session->setFlash('error', "Somthing went wrong,please try again!");
          // return $this->redirect(['resetpassword']);
        }
      }
      else{
        Yii::$app->session->setFlash('error', "New Password and Confirm Password does not match!");
        return $this->redirect(['resetpassword']);
      }
    }
      
      
      return $this->render('resetpassword');
    }

    public function actionBranch(){
      $this->layout='common';
      $model= new BranchUpload();
      if ($model->load(Yii::$app->request->post())) {
        $imagep = UploadedFile::getInstance($model, 'File');
      if($imagep)
        {
         $imageID=$model->imageUpload($imagep);
       }
       else
       {
         $imageID='';
       }
       $model->File=$imageID;
       $model->OnDate=date('Y-m-d H:i:s');
       $model->UploadedBy=Yii::$app->user->identity->UserId;
       // echo"<pre>" ;var_dump($model);echo "</pre>";
       if($model->save())
       {
        $basepath=Yii::getAlias('@storage').'/uploads/';
        $inputFileType='Xlsx';
        $inputFileName=$basepath.$imageID;
        $reader= \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet=$reader->load($inputFileName);
        $sheetcount=$spreadsheet->getSheetCount();
        for ($i = 0; $i < $sheetcount; $i++)
        {
       $sheet = $spreadsheet->getSheet($i);
       $sheetrows = $sheet->getHighestRow();
       for($row = 2; $row<=$sheetrows; $row++)
       {
        $BranchId = (string)$sheet->getCellByColumnAndRow(2,$row);
        $BranchName = (string)$sheet->getCellByColumnAndRow(3,$row);
        $Cluster = (string)$sheet->getCellByColumnAndRow(4,$row);
        $State = (string)$sheet->getCellByColumnAndRow(5,$row);
        $Entity = (string)$sheet->getCellByColumnAndRow(6,$row);

        $models= New  BranchMaster();
      $models->BranchId=$BranchId;
      $models->BranchName=$BranchName;
      $models->Cluster=$Cluster;
      $models->State=$State;
      $models->Entity=$Entity;
      $models->Ondate=date('Y-m-d H:i:s');
      $models->save();
           Yii::$app->session->setFlash('success', "Uploaded successfully");
       }
       }
       }
       else
        {
          var_dump($model->getErrors());die();
        Yii::$app->session->setFlash('error', "Cant Uploaded ");
       }

  
      }
      $details = BranchMaster::find()->where(['IsDelete' => 0])->all();
      return $this->render('branch',['model'=>$model,'details'=>$details]);

    }


    public function actionLogout()
    {
      Yii::$app->user->logout();
      
      return $this->goHome();
    }
  }

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
use common\models\TXNRESPDETAILS;
use common\models\ExportExcel;

use yii\data\Pagination;
use yii\base\Widget;
use yii\widgets\LinkPager;
use yii\db\Expression;
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
      'actions' => ['login', 'error','subadmin','process','mfi','mfidetails','mfidet','msmedet','previous','msme','adminprev','msmedetails','updatemfi','both','updatemsme','sendsms','mfisms','adminview','delete','url','report','customerdetails','resetpassword','subaddresetpassword','branch','paymentdetails','deleterecord','cust-payment','report2'],
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
    
    public function actionSendsms($id,$type,$mon)
      {
       //if($type=='MSME')
       //  $model=new MsmeExcelData();
       //else
       //  $model= new ExcelData();
       $model=new MsmeExcelData();
       $excel= $model->updateAll(['SmsStatus'=>2],['RecordId'=>$id,'IsDelete'=>0,'SmsStatus'=>null]);
       
        return $this->redirect(['msme','id'=>$id,'type'=>$type,'mon'=>$mon]);
      
      }
      public function actionSendsms2($id,$type,$mon)
      { 
       $excel = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->one();
        
        $mon=$upload->MonthYear;
       foreach ($excel as $key => $value) {
        $date=date ('d-m-Y',strtotime($value->DemandDate));
        $Mobile=$value->MobileNo;
        $RecordId=$value->Mid;
        $rec=$value->RecordId;
        $Type=$value->Type;
        $loan=$value->LoanAccountNo;
        $amt=$value->CurrentMonthDue +  $value->LatePenalty+$value->LastMonthDue;
        $request ="";
        $shorturl = $this->getUrl($rec,$Type,$loan);
        $acctNo=$value->LoanAccountNo;
        $due=$value->DemandDate;
        //  var_dump(strlen($Mobile));die();
        if ($Mobile != '') {

           if ( strlen($Mobile) == 10 ) {
             
            $param['send_to'] = $value->MobileNo;
            $param['method']= "sendMessage";
            
            //$param['msg'] = "Dear Mr./Mrs/Ms " .$value->ClientName." Your EMI Installment is due on date ".$date. " for Annapurna Loan account no ".$value->LoanAccountNo." .Please visit ".$shorturl." to pay on line or at the nearest service branch to improve your Credit Bureau Score. ";
            
            $param['msg']="Dear Customer, EMI of Rs. $amt for Annapurna loan a/c no. $acctNo is due on $due. Pls make online payment at $shorturl or at the nearest service branch to avoid the extra charges.";
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
    $amt=$value->CurrentMonthDue +  $value->LatePenalty+$value->LastMonthDue;
    $shorturl = $this->getMfiUrl($rec,$Type,$loan);
    $param['send_to'] = $Mobile;
    if ($Mobile != '') {
     if (strlen($Mobile) == 10) {
       $param['method']= "sendMessage"; 
    //$param['msg'] = "Dear Mr./Mrs/Ms "  .$value->ClientName." Your Installment is due on date ".$date. " for Annapurna Loan acocunt no ".$value->LoanAccountNo." Please visit ".$shorturl." to pay on line or at the nearest service branch to improve your Credit Bureau Score.";
    $param['msg']="Dear Customer, EMI of Rs. $amt for Annapurna loan a/c no. $loan is due on $date. Pls make online payment at $shorturl or at the nearest service branch to avoid the extra charges.";
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
  if ($imagep->extension=='xlsx'){
 
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
                    
                    $invalidcount=0;
                    $rownos='';
                    
                    $empty='';
                    if($empty=='')
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
                        if ( trim($ProductVertical) == 'MFI') {
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
                          $errorclient=ExcelData::find()->where(['UploadMonth'=>$UploadMonth,'ClientId'=>$ClientId])->count();
                  $errormobile=ExcelData::find()->where(['UploadMonth'=>$UploadMonth,'MobileNo'=>$MobileNo])->count();
                  $errorEmiSrNo=(is_numeric($EmiSrNo) && $EmiSrNo<240)?0:1;
                  $LastMonthDueError=(is_numeric($LastMonthDue) && strlen($LastMonthDue)<8)?0:1;
                  $CurrentMonthDueError=(is_numeric($CurrentMonthDue) && strlen($CurrentMonthDue)<8)?0:1;
                  $DemandDateError=($uni=='')?1:0;
                  $ClusterError =(trim($Cluster)=='')? 1:0;
                  $BranchNameError =(trim($BranchName)=='')? 1:0;
                  $ClientNameError =(trim($ClientName)=='' && !(strlen($ClientName)>2 && strlen($ClientName)<21))? 1:0;
                  $errorLoanAccountNo =ExcelData::find()->where(['UploadMonth'=>$UploadMonth,'LoanAccountNo'=>$LoanAccountNo])->count();
                  $errormoblength=(strlen($MobileNo)!=10)? 1:0;
                  //echo "$errorclient+$errormobile+$errorEmiSrNo+$DemandDateError+$errorLoanAccountNo+$errormoblength";
                  $toterror=$errorclient+$errormobile+$errorEmiSrNo+$DemandDateError+$errorLoanAccountNo+$errormoblength+$BranchNameError+$ClientNameError+$ClusterError+$LastMonthDueError
                            +$CurrentMonthDueError;
                  //$msme->errorCount=$toterror;
                  $errormsg=null;
                  if($errorclient>0){
                   $errormsg.=' duplicate Clientid';
                  }
                  if($errorEmiSrNo>0){
                   $errormsg.=' duplicate emisr of this loanAc';
                  }
                  if($errormobile>0){
                   $errormsg.='this mobile no exists in this month';
                  }
                  if($errormoblength>0)
                  $errormsg.=' not a valid mobile number';
                  if($errorLoanAccountNo>0)
                   $errormsg.=' this loanAcct already exist for this month';
                  if($DemandDateError>0)
                   $errormsg.='Demand date cannot be empty';
                  if($BranchNameError>0)
                  $errormsg.='BranchName cannot be empty';
                  if($ClientNameError>0)
                  $errormsg.='Client name cant be empty';
                  if($ClusterError>0)
                  $errormsg.='cluster cannot be blank';
                  if($LastMonthDueError>0)
                  $errormsg.='Lastmonth due error';
                  if($CurrentMonthDueError>0)
                  $errormsg.='CurrentMonthDue due error';
                  
                  $models->errorMsg=$errormsg;
                  if($toterror>0)
                  $models->errorCount=1;
                          // echo "<pre>";
                          // var_dump($models);die(); echo "</pre>";
                          if ($models->save()) {
                            $count=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0])->one();
                            $mficount = ExcelData::find()->where(['RecordId'=>$model->RecordId,'IsDelete' => 0])->count();
                            if ($mficount>0) {
                            $count->Count=$mficount;
                            if ($count->save()) {
                            
                             Yii::$app->session->setFlash('success','File Uploaded successfully');
                             //return $this->redirect(['index']);
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
                           Yii::$app->session->setFlash('error','Due to Wrong MFI --'.trim($ProductVertical).'-- format,File can not be uploaded!');
                          
                        }  
                    }
                  }
           }  //echo "<pre>";
                    //  var_dump($models);echo "</pre>";
                   // die();
                   $mismatch=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0,'Type'=>'MFI'])->one();
    

   $otherError=ExcelData::find()->where(['RecordId'=>$model->RecordId,'IsDelete' => 0])->andWhere(['!=','errorCount','0'])->count();
   
      $mismatch->Mismatch =$otherError ;
    

    
     
      $mismatch->save();
    

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
           $invalidcount=0;
           $rownos='';
           $empty='';
           for($i=2;$i<=16;$i++){
            if(trim((string)$sheet->getCellByColumnAndRow($i,$row))==''){
             $empty.='NA'.'-row-'.$i;
            }
           }
           $empty='';
           if($empty=='')
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
            
              if (trim($ProductVertical) == 'MSME') {
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
                  $errorclient=MsmeExcelData::find()->where(['UploadMonth'=>$UploadMonth,'ClientId'=>$ClientId])->count();
                  $errormobile=MsmeExcelData::find()->where(['UploadMonth'=>$UploadMonth,'MobileNo'=>$MobileNo])->count();
                  
                  $DemandDateError=($uni=='')?1:0;
                  $ClientNameError =(trim($ClientName)=='')? 1:0;
                  $errorLoanAccountNo =MsmeExcelData::find()->where(['UploadMonth'=>$UploadMonth,'LoanAccountNo'=>$LoanAccountNo])->count();
                  $errormoblength=(strlen($MobileNo)!=10)? 1:0;
                  $errorEmiSrNo=(is_numeric($EmiSrNo) && $EmiSrNo<240)?0:1;
                  $LastMonthDueError=(is_numeric($LastMonthDue) && strlen($LastMonthDue)<8)?0:1;
                  $CurrentMonthDueError=(is_numeric($CurrentMonthDue) && strlen($CurrentMonthDue)<8)?0:1;
                  $LoanAccountNoError=(strlen($LoanAccountNo)==20)?0:1;
                  $ClusterError =(trim($Cluster)=='')? 1:0;
                  $BranchNameError =(trim($BranchName)=='')? 1:0;
                  $ClientNameError =(trim($ClientName)=='' && !(strlen($ClientName)>2 && strlen($ClientName)<21))? 1:0;
                  
                   $toterror=$errorclient+$errormobile+$errorEmiSrNo+$DemandDateError+$errorLoanAccountNo+$errormoblength+$BranchNameError+$ClientNameError+$ClusterError+$LastMonthDueError
                            +$CurrentMonthDueError+$LoanAccountNoError;
                  $msme->errorCount=$toterror;
                  $errormsg=null;
                  if($errorclient>0){
                   $errormsg.=' duplicate Clientid';
                  }
                  if($errorEmiSrNo>0){
                   $errormsg.=' duplicate emisr of this loanAc';
                  }
                  if($LoanAccountNoError>0)
                  $errormsg.=' not valid loan account number ';
                  if($errormobile>0){
                   $errormsg.=' this mobile no exists in this month';
                  }
                  if($errormoblength>0)
                  $errormsg.=' not a valid mobile number';
                  if($errorLoanAccountNo>0)
                   $errormsg.=' this loanAcct already exist for this month';
                  if($DemandDateError>0)
                   $errormsg.='Demand date cannot be empty';
                  if($BranchNameError>0)
                  $errormsg.='BranchName cannot be empty';
                  if($ClientNameError>0)
                  $errormsg.='Client name cant be empty';
                  if($ClusterError>0)
                  $errormsg.='cluster cannot be blank';
                  if($LastMonthDueError>0)
                  $errormsg.='Lastmonth due error';
                  if($CurrentMonthDueError>0)
                  $errormsg.='CurrentMonthDue due error';
                  $msme->errorMsg=$errormsg;
                  if($toterror>0)
                  $msme->errorCount=1;
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
                   Yii::$app->session->setFlash('error','Due to Wrong MSME--'.trim($ProductVertical).'--format,some of rows from File can not be uploaded!');
                  
                }  
       
          }
          //else{
          // $invalidcount+=1;
          // $rownos += $row.', ';
          // Yii::$app->session->setFlash('error','Due to Wrong empty account no  --'.$invalidcount.'--,some of rows from File can not be uploaded! rows are '.$rownos);
          //}
          
        }
        //die();
 }
 $mismatch=UploadRecords::find()->where(['RecordId'=>$model->RecordId,'IsDelete'=>0,'Type'=>'MSME'])->one();
 $connection = \Yii::$app->db;

 
  $otherError=MsmeExcelData::find()->where(['RecordId'=>$model->RecordId,'IsDelete' => 0])->andWhere(['!=','errorCount','0'])->count();
  
        // var_dump($result);die();
       
   

  $mismatch->Mismatch=$otherError ;



  $mismatch->save();

}
}

else{
 var_dump($model->getErrors());
}


 }
 else
 {
  Yii::$app->session->setFlash('error','Please only upload .xlsx extention file');
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
$MonthYear=array_slice($MonthYear, (date('m')-1),2); 
  $typee=Yii::$app->user->identity->Type;
  $uid=Yii::$app->user->identity->UserId;

  if ($typee !="both") {

   $details = UploadRecords::find()->where(['UploadedBy'=>$uid,'Type'=>$typee,'IsDelete' => 0])->andWhere(['!=','Count','0'])->orderBy(['RecordId'=>SORT_DESC])->all();

 }
 
  $details = UploadRecords::find()->where(['UploadedBy'=>$uid,'IsDelete' => 0])->andWhere(['!=','Count','0'])->orderBy(['RecordId'=>SORT_DESC])->all();
  return $this->render('home', ['model' => $model, 'MonthYear' => $MonthYear, 'alldetails' => $details]);

}

public function actionDeleterecord(){
 $recordid=Yii::$app->request->get('recordid');
 $type=Yii::$app->request->get('type');
 $dlt=Yii::$app->request->get('dlt');
 if($type='MSME')
 {
 $model='MsmeExcelData';
 $cntModel= new MsmeExcelData();
 }
 else
 {
 $model='ExcelData';
 $cntModel=new ExcelData();
 }
 $only='';
 if($dlt=='only')
  $only.=" and errorCount=1";
 $count='0';
 echo $model;
 $del=Yii::$app->db->createCommand()
        ->delete($model, "RecordId = $recordid".$only)
        ->execute();
  //$query->createCommand()->getRawSql();
  
 $details = UploadRecords::find()->where(['RecordId'=>$recordid])->one();
  if($only=='')
  {
 
  $details->delete();
  }
  else{
   $cntModel=$cntModel->find()->where(['RecordsId'=>$recordid])->count();
   $details->NoOfRecords=$cntModel;
   $details->Mismatch=0;
   $details->save();
   
   
  }
  return $this->redirect(['index']);
 
}
public function actionBoth($id,$type,$mon){

  $uid=Yii::$app->user->identity->UserId;
  $role=Yii::$app->user->identity->Role;

  if($role==1)
    $types=UploadRecords::find()->where(['IsDelete' => 0,'RecordId'=>$id])->one();
  else
    $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'IsDelete' => 0,'RecordId'=>$id])->one();


  if ($types->Type == "MFI") {

    return $this->redirect(['mfi', 'id' => $types->RecordId, 'type'=>$type,'mon'=>$types->MonthYear,'pagination'=>'show']);
  }
  else
  {
    return $this->redirect(['msme','id'=>$types->RecordId, 'type'=>$type,'mon'=>$types->MonthYear,'pagination'=>'show']);
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

public function actionMsme($id,$type,$mon,$error=false)
{   
  $this->layout = 'common';
  list($month, $year) =explode("/",$mon);
  $month = date("m",strtotime($month));
  $getid=array();

  if($error){
   $details = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])
             ->andWhere(['!=','errorMsg','0']);
             
  }
  else{
  $details = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0]);
  }
  $pages=0;
  if(!empty(yii::$app->request->get('pagination'))){
  $countQuery = clone $details;
  $pages = new Pagination(['totalCount' => $countQuery->count()]);
  if ($pages) {
            $details = $details->offset($pages->offset)
                               ->limit($pages->limit);
          }
        }
           $details=$details->all();

  if (!empty(yii::$app->request->get('export'))) {
    $excel= new ExportExcel();
    $sheet=[];
    foreach ($details as $key => $details) {
      $sheet['BranchName'][]=$details->BranchName;
      $sheet['Cluster'][]=$details->Cluster;
      $sheet['State'][]=$details->State;
      $sheet['ClientId'][]=$details->ClientId;
      $sheet['LoanAccountNo'][]=$details->LoanAccountNo;
      $sheet['ClientName'][]=$details->ClientName;
      //$sheet['ClientName'][]=$details->MobileNo;
      $sheet['MobileNo'][]=$details->MobileNo;
      $sheet['EmiSrNo'][]=$details->EmiSrNo;
      $sheet['DemandDate'][]=date('d-m-Y',strtotime($details->DemandDate));
      $sheet['LastMonthDue'][]=number_format($details->LastMonthDue,2);
      $sheet['CurrentMonthDue'][]=number_format($details->CurrentMonthDue,2);
      $sheet['LatePenalty'][]=number_format($details->LatePenalty,2);
      $sheet['NextInstallmentDate'][]=date('d-m-Y',strtotime($details->NextInstallmentDate));
      $sheet['UploadMonth'][]=$details->UploadMonth;
      $sheet['ProductVertical'][]=$details->ProductVertical;
      $sheet['Tiny URL'][]=$details->TinnyUrl;
      $sheet['SmsStatus'][]=($details->SmsStatus == 1)?'Initiated':'Not Initiated';
    }
    $excel->Export($sheet);
  }

  $approve= MsmeExcelData::find()->where(['RecordId'=>$id,'IsApproved'=>1,'IsDelete'=>0])->count();
   $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
   foreach ($upload as $key => $value) {
   $mon=$value->MonthYear;
   }
  
  $getallid=MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0]) 
               ->andWhere(['!=','errorCount','0'])  
               ->all();


  $getblankvalue = count($getallid);
 
  foreach ($getallid as $key => $value) {
    //var_dump($value);
   $getid[]= $value->Mid;
 }
 // foreach ($details as $key => $value) {
 //  $sms=$value->SmsStatus;
  
 //   $paymetdetails = TXNDETAILS::find()->select('SUM(TXN_AMT) as TXN_AMT,TXN_STATUS' )->where(['USER_ID'=>$value->Mid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1,'Type'=>'MSME'])->one();
 // }
 // if(empty($sms))
 // {
 //  $sms='NA';
 //  $paymetdetails=['TXN_AMT'=>'NA','TXN_STATUS'=>'NA'];
 // }
 // var_dump($details);die();
 return $this->render('msme',['details'=>$details,'id'=>$id,'approve'=>$approve,'getid'=>$getid,'sms'=>'','getblankvalue'=>$getblankvalue,'type'=>$type,'paymetdetails'=>'','mon'=>$mon,'error'=>$error,'pages'=>$pages]);

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
public function actionUpdatemfi($id,$type,$mon){
  $this->layout = 'common';
  $model = ExcelData::find()->where(['Eid'=> $id,'IsDelete'=>0])->one();
   $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }
  $details = $model->RecordId;
  $model->errorMsg=null;
  $model->errorCount=0;
  if ($model->load(Yii::$app->request->post()) && $model->save()) {
   $uid=Yii::$app->user->identity->UserId;
   $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();
        //$mismatch=UploadRecords::find()->where(['RecordId'=>$types->RecordId,'IsDelete'=>0])->one();
   $connection = \Yii::$app->db;
   $command = $connection->createCommand("SELECT COUNT(*) as cnt FROM `ExcelData` WHERE `RecordId` = '$types->RecordId' and `IsDelete`= 0 GROUP BY LoanAccountNo HAVING cnt > 1 ");
   $result = $command->queryAll(); 
          
   if ($result) {
     //$types->Mismatch=$result[0]['cnt'];
     $types->Mismatch=0;
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
      return $this->redirect(['mfi', 'id' => $types->RecordId,'type'=>$type,'mon'=>$mon]);
    }
  }

}
return $this->render('mfi_edit',['model'=>$model,'details'=>$details,'type'=>$type,'mon'=>$mon]); 

}
public function actionUpdatemsme($id,$type,$mon){
  $this->layout='common';
  $model= MsmeExcelData::find()->where(['Mid'=>$id,'IsDelete'=>0])->one();
  $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }
  $details=$model->RecordId;
  $model->errorMsg='';
  $model->errorCount=0;
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
    /*if ($types->save()) {
      $connection = \Yii::$app->db;
      $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from `MsmeExcelData` WHERE `RecordId`='$types->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
      $result =$mismatchmobile->queryAll();
      $types->MobileCount=$result[0]['cnt'];
    } */
    if ($types->save()) {
     Yii::$app->session->setFlash('success', "Account Updated successfully");
     return $this->redirect(['msme', 'id' => $types->RecordId,'type'=>$type,'mon'=>$mon]);
   }
 }
 return $this->render('msme_edit',['model'=>$model,'type'=>$type,'mon'=>$mon]);
}

public function actionUploaded()
{
 $this->layout = 'common';
 return $this->render('uploaded_view');
}
private function getUrl($RecordId,$Type,$loan){
      //$excel = MsmeExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
  $link = urlencode(Yii::getAlias('@frontendUrl').'/site/login?id='.$RecordId.'&typ='.$Type.'&l='.$loan);
  $key='91a58dbc5e02d2897e9c6eb5d9466f3d2ca17';
  $url='https://cutt.ly/api/api.php';
  $json = file_get_contents($url."?key=$key&short=$link"); 
  $data=json_decode($json,true);

  return $data["url"]['shortLink']; 
}
private function getMfiUrl($RecordId,$Type,$loan){
 // $excel = ExcelData::find()->where(['RecordId'=>$id,'IsDelete'=>0])->one();


 $link = urlencode(Yii::getAlias('@frontendUrl').'/site/login?id='.$RecordId.'&typ='.$Type.'&l='.$loan);
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

/*public function actionPaymentreport(){
       $this->layout = 'common';
      $getpayment=Yii::$app->db->createCommand("SELECT tb.RecordId, tb.BranchName,tb.Cluster,tb.State,tb.ClientId,tb.LoanAccountNo,tb.ClientName,tb.MobileNo,tb.EmiSrNo,tb.DemandDate,tb.LastMonthDue,tb.CurrentMonthDue,tb.LatePenalty,tb.NextInstallmentDate,tb.UploadMonth,tb.ProductVertical,txn.MONTH as txnmonth,txn.TXN_DATE as txndate,txn.TXN_AMT as txnamnt,txn.TXN_STATUS FROM MsmeExcelData tb LEFT JOIN TXN_DETAILS txn ON tb.Mid = txn.USER_ID UNION ALL SELECT tbex.RecordId, tbex.BranchName,tbex.Cluster,tbex.State,tbex.ClientId,tbex.LoanAccountNo,tbex.ClientName,tbex.MobileNo,tbex.EmiSrNo,tbex.DemandDate,tbex.LastMonthDue,tbex.CurrentMonthDue,tbex.LatePenalty,tbex.NextInstallmentDate,tbex.UploadMonth,tbex.ProductVertical,txnn.MONTH as txnmonth,txnn.TXN_DATE as txndate,txnn.TXN_AMT as txnamnt,txnn.TXN_STATUS FROM ExcelData tbex LEFT JOIN TXN_DETAILS txnn ON tbex.Eid = txnn.USER_ID");
      $getallreport =$getpayment->queryAll();

      return $this->render('paymentreport',['getallreport'=>$getallreport]);

    }*/


    public function actionPaymentdetails(){
       $this->layout = 'common';
       $lnacno='';
       $getpayment = TXNDETAILS::find();
       if (isset(Yii::$app->request->post()['search'])) {
          $lnacno = Yii::$app->request->post('loanacno');
          $getpayment=$getpayment->where(['LOAN_ID'=>$lnacno]);
       }
       
         $pages=0;
         if (!empty(yii::$app->request->get('pagination')))
         {
          $countQuery = clone $getpayment;
          $pages = new Pagination(['totalCount' => $countQuery->count()]);
          if ($pages) {
            $getpayment = $getpayment->offset($pages->offset)
                               ->limit($pages->limit);
          }

         }
      $getallreport =$getpayment->all();


       if(yii::$app->request->get('export')){
           $excel=new ExportExcel();
           $sheet=[];
            $amount=[];
            $previouslypaid=[];
           foreach($getallreport as $key => $value){
             $totamnt=($value->usermser->LastMonthDue+$value->usermser->LatePenalty+$value->usermser->CurrentMonthDue);
            
               if(!isset($previouslypaid[$value->LOAN_ID]) )
                  $previouslypaid[$value->LOAN_ID]=0;
               
                $dueamt=$totamnt-($previouslypaid[$value->LOAN_ID]);
              
               
               $sheet['Type'][]=$value->TYPE;
               $sheet['Branch Name'][]=$value->usermser->BranchName;
               $sheet['User Id'][]=$value->USER_ID;
               $sheet['Client Name'][]=$value->usermser->ClientName;
               $sheet['Mobile No'][]=$value->usermser->MobileNo;
               $sheet['Loan Account No.'][]=$value->LOAN_ID;
               $sheet['Transaction Date'][]=date('d-m-Y H:i:s',strtotime($value->TXN_DATE));
               $sheet['Bank Ref. No.'][]=(isset($value->transactionres->WALLET_BANK_REF))?$value->transactionres->WALLET_BANK_REF:'';
               $sheet['Demand Date'][]=date('d-m-Y',strtotime($value->usermser->DemandDate));
               $sheet['Demand Amount'][]=number_format($totamnt,2);
               $sheet['Previous Received Amount'][]=number_format($previouslypaid[$value->LOAN_ID],2);
               $sheet['Due Amount'][]=number_format($dueamt,2);
               $sheet['Receipt Amount'][]=number_format($value->TXN_AMT,2);
               $sheet['Receipt Mode'][]=(isset($value->transactionres->PG_MODE))?$value->transactionres->PG_MODE:'';
               $sheet['Next Installment Date'][]=date('d-m-Y',strtotime($value->usermser->NextInstallmentDate));
               
               //$sheet['Total Amount Paid'][]=number_format($previouslypaid[$value->LOAN_ID]+$value->TXN_AMT,2);
               $sheet['Status'][]=(($value->TXN_STATUS) == 1)?'Success':'Failed';
               if($value->TXN_STATUS==1){
                $previouslypaid[$value->LOAN_ID]+=$value->TXN_AMT;
               }
           }
           $excel->Export($sheet);
          
         }

      return $this->render('paymentdetails',['getallreport'=>$getallreport,'lnacno'=>$lnacno,'pages'=>$pages]);

    }
   

    public function actionReport()
        { 
          $model= new MsmeExcelData();
          $this->layout = 'common';
          $zonename=$branchname=$product=$type='';
          $fromdate=date('Y-m-01');
          $todate=date('Y-m-d', strtotime(date('Y-m-d').'+1day'));
          $ttdate=date('Y-m-d');
          $frmdate=date('d-m-Y',strtotime($fromdate));
          $tdate=date('d-m-Y',strtotime($ttdate));
          $type=isset(Yii::$app->request->get()['type'])?Yii::$app->request->get()['type']:'';
           $branchname=isset(Yii::$app->request->get()['branchname'])?Yii::$app->request->get()['branchname']:'';
          $fromdate=isset(Yii::$app->request->get()['fromdate'])?Yii::$app->request->get()['fromdate']:$fromdate;
          $todate=isset(Yii::$app->request->get()['todate'])?Yii::$app->request->get()['todate']:$todate;
           if (isset(Yii::$app->request->get()['fromdate'])) {
            $frmdate=date('d-m-Y',strtotime($fromdate));
          }
          
          if (isset(Yii::$app->request->get()['todate'])) {
            $tdate=date('d-m-Y',strtotime($todate));
          }
            
          if($type=='MFI'){
           $mod= new ExcelData();
          }
          else
          {
           $mod= new MsmeExcelData();
          }
          
          

          $allcustomer=$alldetails=array();
          $branchnnm=ArrayHelper::map(MsmeExcelData::find()->where(['IsDelete'=>0])->groupBy(['BranchName'])->all(),'BranchName','BranchName');
          $allcustomer = $mod->find()->select(['RecordId','BranchName','Cluster','State','ProductVertical','DemandDate','count(ClientId) as totalcustomer'])->where(['IsDelete' => 0])->groupBy(['BranchName']);
          $branchpayment=new MsmeExcelData();
          
          //if (isset(Yii::$app->request->get()['report_search'])) {
            //$type=Yii::$app->request->get()['type']?Yii::$app->request->get()['type']:'';
           
            
            $fromdate=date('Y-m-d',strtotime($fromdate));
            $todate=date('Y-m-d', strtotime(date('Y-m-d').'+1day'));
            if ($branchname) {
              $allcustomer=$allcustomer->andWhere(['BranchName'=>$branchname]);
            }
           /* if ($type) {
              $allcustomer=$allcustomer;
            }*/
           
            
           /* $fromdate=date('Y-m-d', strtotime($fromdt));
            $todate=date('Y-m-d', strtotime($todt. ' +1 day'));*/

          
        //}
        $allcustomer=$allcustomer->andWhere(['between','OnDate',$fromdate,$todate]);
        $pages=0;
         if (!empty(yii::$app->request->get('pagination')))
         {
          $countQuery = clone $allcustomer;
          $pages = new Pagination(['totalCount' => $countQuery->count()]);
          if ($pages) {
            $allcustomer = $allcustomer->offset($pages->offset)
                               ->limit($pages->limit);
          }

         }

        $allcustomer=$allcustomer->all();


          if(yii::$app->request->get('export')){
           $excel=new ExportExcel();
           $sheet=[];
           $branches=[];
          /* var_dump($allcustomer);
           die();*/
           foreach($allcustomer as $key => $value){
            $branches[$value->BranchName]=$branchpayment->branchpayment($value->RecordId,$value->BranchName,$fromdate,$todate);
           $sheet['State'][]=$value->State;
           $sheet['Cluster'][]=$value->Cluster;
           $sheet['Branch'][]=$value->BranchName;
           $sheet['Type'][]=$value->ProductVertical;
           $sheet['Month'][]=date('M-Y', strtotime($value->DemandDate));
           $sheet['Amount Collected'][]=number_format($branches[$value->BranchName]->amountcollected,2);
           $sheet['No. of Customers'][]=$value->totalcustomer;
           $sheet['Customers Paid'][]=$branches[$value->BranchName]->customerpaid;
           }
           $excel->Export($sheet);
          
         }


        $branches=[];
        foreach($allcustomer as $customer){
         $branches[$customer->BranchName]=$branchpayment->branchpayment($customer->RecordId,$customer->BranchName,$fromdate,$todate);
        }
         return $this->render('report', [
          'model'=>$model,'allcustomer' => $allcustomer,'branchnnm'=>$branchnnm,'branchname'=>$branchname,'type'=>$type,'fromdate'=>$fromdate,'todate'=>$todate,'branchpayment'=>$branches,'pages'=>$pages,'frmdate'=>$frmdate,'tdate'=>$tdate
          ]);
      }

     public function actionReport2()        { 
          $model= new MsmeExcelData();
          $this->layout = 'common';
          $zonename=$branchname=$product=$type='';
           $type=isset(Yii::$app->request->get()['type'])?Yii::$app->request->get()['type']:'';
          if($type=='MFI'){
           $mod= new ExcelData();
          }
          else
          {
           $mod= new MsmeExcelData();
          }
          $fromdate=date('Y-m-01');
          $todate=date('Y-m-d', strtotime(date('Y-m-d'). ' +1 day'));
          $ttdate=date('Y-m-d');
          $frmdate=date('d-m-Y',strtotime($fromdate));
          $tdate=date('d-m-Y',strtotime($ttdate));
          $allcustomer=$alldetails=array();

          $branchnnm=ArrayHelper::map(MsmeExcelData::find()->where(['IsDelete'=>0])->groupBy(['BranchName'])->all(),'BranchName','BranchName');

          $allcustomer = $mod->find()
          ->select(['RecordId','BranchName','Cluster','State','ProductVertical','DemandDate','count(ClientId) as totalcustomer'])
          ->where(['IsDelete' => 0])
          ->groupBy(['BranchName']);
          $branchpayment=new MsmeExcelData();


          if (isset(Yii::$app->request->get()['report_search'])) {
            $fromdate=Yii::$app->request->get()['fromdate']?Yii::$app->request->get()['fromdate']:$fromdate;
            $todate=Yii::$app->request->get()['todate']?Yii::$app->request->get()['todate']:$todate;
            $type=Yii::$app->request->get()['type']?Yii::$app->request->get()['type']:'';
            $branchname=Yii::$app->request->get()['branchname']?Yii::$app->request->get()['branchname']:'';

            $frmdate=date('d-m-Y',strtotime($fromdate));
            $tdate=date('d-m-Y',strtotime($todate));
           /* if($fromdt)
            {
             $allcustomer=$allcustomer->andWhere(['between','OnDate',$fromdt,$todt]);
            }*/
            if ($branchname) {
              $allcustomer=$allcustomer->andWhere(['BranchName'=>$branchname]);
            }
            $fromdate=date('Y-m-d', strtotime($fromdate));
            $todate=date('Y-m-d', strtotime($todate. ' +1 day'));  
        }
        $allcustomer=$allcustomer->andWhere(['between','OnDate',$fromdate,$todate]);

        $pages=0;
         if (!empty(yii::$app->request->get('pagination')))
         {
          $countQuery = clone $allcustomer;
          $pages = new Pagination(['totalCount' => $countQuery->count()]);
          if ($pages) {
            $allcustomer = $allcustomer->offset($pages->offset)
                               ->limit($pages->limit);
          }

         }
        
        $allcustomer=$allcustomer->all();


        if(yii::$app->request->get('export')){
           $excel=new ExportExcel();
           $sheet=[];
           $branches=[];
           foreach($allcustomer as $key => $value){
            $branches[$value->BranchName]=$branchpayment->branchpayment($value->RecordId,$value->BranchName,$fromdate,$todate);
           $sheet['State'][]=$value->State;
           $sheet['Cluster'][]=$value->Cluster;
           $sheet['Branch'][]=$value->BranchName;
           $sheet['Type'][]=$value->ProductVertical;
           $sheet['Month'][]=date('M-Y', strtotime($value->DemandDate));
           $sheet['Amount Collected'][]=number_format($branches[$value->BranchName]->amountcollected,2);
           $sheet['No. of Customers'][]=$value->totalcustomer;
           $sheet['Customers Paid'][]=$branches[$value->BranchName]->customerpaid;
           }
           $excel->Export($sheet);
          
         }



        $branches=[];
        foreach($allcustomer as $customer){
         $branches[$customer->BranchName]=$branchpayment->branchpayment($customer->RecordId,$customer->BranchName,$fromdate,$todate);
        }

         return $this->render('report2', [
          'model'=>$model,'allcustomer' => $allcustomer,'branchnnm'=>$branchnnm,'branchname'=>$branchname,'type'=>$type,'fromdate'=>$fromdate,'todate'=>$todate,'branchpayment'=>$branches,'pages'=>$pages,'frmdate'=>$frmdate,'tdate'=>$tdate
          ]);
      }


    /**
     * Logout action.
     *
     * @return string
     */

   public function actionCustomerdetails($branch,$fromdt,$todt)
      {   
       $this->layout = 'common';
       $txnsts=array();
       //if ($type == 'MSME') {
       $details = MsmeExcelData::find()->where(['between','OnDate',$fromdt,$todt])
                                         ->andWhere(['IsDelete' => 0]);
         $pages=0;
         if(yii::$app->request->get('branch')!='')
          {
           $details=$details->andWhere(['BranchName'=>$branch]);
          }
         if (!empty(yii::$app->request->get('pagination')))
         {
          $countQuery = clone $details;
          $pages = new Pagination(['totalCount' => $countQuery->count()]);
          if ($pages) {
            $details = $details->offset($pages->offset)
                               ->limit($pages->limit);
          }

         }
         
         $details=$details->all();
         
         
         if(yii::$app->request->get('export')){
          
           $excel=new ExportExcel();
           $sheet=[];
           foreach($details as $key=>$detail){
            $paymetdetails[$detail->Mid] = TXNDETAILS::find()->joinWith(['transactionres','usermser'])->select(['SUM(TXN_DETAILS.TXN_AMT) as TXN_AMT','TXN_DETAILS.TXN_DATE','GROUP_CONCAT(TXN_RESP_DETAILS.WALLET_BANK_REF SEPARATOR ",") as WALLET_BANK_REF','GROUP_CONCAT(TXN_RESP_DETAILS.PG_MODE SEPARATOR ",") as PG_MODE'] )->where(['TXN_DETAILS.USER_ID'=>$detail->Mid,'TXN_DETAILS.TXN_STATUS'=>1])->andWhere(['between','date(TXN_DETAILS.TXN_DATE)',$fromdt,$todt])->one();
             $totalamt=$detail->LastMonthDue+$detail->CurrentMonthDue+$detail->LatePenalty;
              $paid=$paymetdetails[$detail->Mid]->TXN_AMT;
              if(!$paid){
               $paid=0;
              }
              $due=$totalamt-$paid;
             if($due>0)
              $pay_status='Partial';
             elseif($due<=0)
             $pay_status='Complete';
             if($paid==0)
             $pay_status='Nill';
           $sheet['Branch Name'][]=$detail->BranchName;
           $sheet['Cluster'][]=$detail->Cluster;
           $sheet['State'][]=$detail->State;
           $sheet['ClientId'][]=$detail->ClientId;
           $sheet['LoanAccountNo'][]=$detail->LoanAccountNo;
           $sheet['ClientName'][]=$detail->ClientName;
           $sheet['MobileNo'][]=$detail->MobileNo;
           $sheet['EmiSrNo'][]=$detail->EmiSrNo;
           $sheet['DemandDate'][]=date('d-m-Y',strtotime($detail->DemandDate));
           $sheet['LastMonthDue'][]=number_format($detail->LastMonthDue,2);
           $sheet['CurrentMonthDue'][]=number_format($detail->CurrentMonthDue,2);
           $sheet['LatePenalty'][]=number_format($detail->LatePenalty,2);
           $sheet['NextInstallmentDate'][]=date('d-m-Y',strtotime($detail->NextInstallmentDate));
           $sheet['UploadMonth'][]=$detail->UploadMonth;

           $sheet['Transaction Date'][]=$paymetdetails[$detail->Mid]->TXN_DATE?date('d-m-Y H:i:s',strtotime($paymetdetails[$detail->Mid]->TXN_DATE)):'';
           $sheet['Bank Ref. No.'][]=$paymetdetails[$detail->Mid]->WALLET_BANK_REF;
           $sheet['Receipt Mode'][]=$paymetdetails[$detail->Mid]->PG_MODE;
           $sheet['Receipt Amount'][]=number_format($paymetdetails[$detail->Mid]->TXN_AMT,2);
           $sheet['Due Amount'][]=number_format(($detail->LastMonthDue + $detail->CurrentMonthDue + $detail->LatePenalty)-$paid,2);
           $sheet['Type'][]=$detail->Type;
           $sheet['Status'][]=$pay_status;
           }
           $excel->Export($sheet);
          
         }
         if ($details) {
          foreach ($details as $key => $val) {
           
            $paymetdetails[$val->Mid] = TXNDETAILS::find()->joinWith(['transactionres','usermser'])->select(['SUM(TXN_DETAILS.TXN_AMT) as TXN_AMT','TXN_DETAILS.TXN_DATE','GROUP_CONCAT(TXN_RESP_DETAILS.WALLET_BANK_REF SEPARATOR ",") as WALLET_BANK_REF','GROUP_CONCAT(TXN_RESP_DETAILS.PG_MODE SEPARATOR ",") as PG_MODE'] )->where(['TXN_DETAILS.USER_ID'=>$val->Mid,'TXN_DETAILS.TXN_STATUS'=>1])->andWhere(['between','date(TXN_DETAILS.TXN_DATE)',$fromdt,$todt])->one();
          }
        }
      
       
       return $this->render('customerdetails',['details'=>$details,'paymetdetails'=>$paymetdetails,'pages'=>$pages,'branch'=>$branch,'fromdt'=>$fromdt,'todt'=>$todt]);


      }
    
    public function actionCustPayment($branch,$fromdt,$todt){
       $this->layout = 'common';
       $txnsts=array();
       $details = MsmeExcelData::find()->where(['between','OnDate',$fromdt,$todt])
                                         ->andWhere(['IsDelete' => 0]);
       if (!empty(yii::$app->request->get('pagination')))
         {
          $countQuery = clone $details;
          $pages = new Pagination(['totalCount' => $countQuery->count()]);
          if ($pages) {
            $details = $details->offset($pages->offset)
                               ->limit($pages->limit);
          }

         }
         if(yii::$app->request->get('branch')!='')
          {
           $details=$details->andWhere(['BranchName'=>$branch]);
          }
         
         $details=$details->all();
         
         
         if(yii::$app->request->get('export')){
           $excel=new ExportExcel();
           $sheet=[];
           foreach($details as $key=>$detail){
            $paymetdetails[$detail->Mid] = TXNDETAILS::find()->joinWith(['transactionres','usermser'])->select(['SUM(TXN_DETAILS.TXN_AMT) as TXN_AMT','TXN_DETAILS.TXN_DATE','GROUP_CONCAT(TXN_RESP_DETAILS.WALLET_BANK_REF SEPARATOR ",") as WALLET_BANK_REF','GROUP_CONCAT(TXN_RESP_DETAILS.PG_MODE SEPARATOR ",") as PG_MODE'] )->where(['TXN_DETAILS.USER_ID'=>$detail->Mid,'TXN_DETAILS.TXN_STATUS'=>1])->andWhere(['between','date(TXN_DETAILS.TXN_DATE)',$fromdt,$todt])->one();
             $totalamt=$detail->LastMonthDue+$detail->CurrentMonthDue+$detail->LatePenalty;
              $paid=$paymetdetails[$detail->Mid]->TXN_AMT;
              if(!$paid){
               $paid=0;
              }
              $due=$totalamt-$paid;
             if($due>0)
              $pay_status='Partial';
             elseif($due<=0)
             $pay_status='Complete';
             if($paid==0)
             $pay_status='Pending';
           $sheet['Branch Name'][]=$detail->BranchName;
           $sheet['Cluster'][]=$detail->Cluster;
           $sheet['State'][]=$detail->State;
           $sheet['ClientId'][]=$detail->ClientId;
           $sheet['LoanAccountNo'][]=$detail->LoanAccountNo;
           $sheet['ClientName'][]=$detail->ClientName;
           $sheet['MobileNo'][]=$detail->MobileNo;
           $sheet['EmiSrNo'][]=$detail->EmiSrNo;
           $sheet['DemandDate'][]=date('d-m-Y',strtotime($detail->DemandDate));
           $sheet['LastMonthDue'][]=number_format($detail->LastMonthDue,2);
           $sheet['CurrentMonthDue'][]=number_format($detail->CurrentMonthDue,2);
           $sheet['LatePenalty'][]=number_format($detail->LatePenalty,2);
           $sheet['NextInstallmentDate'][]=date('d-m-Y',strtotime($detail->NextInstallmentDate));
           $sheet['UploadMonth'][]=$detail->UploadMonth;
           $sheet['Total Emi Amount'][]=number_format(($detail->LastMonthDue + $detail->CurrentMonthDue + $detail->LatePenalty),2);
           //$sheet['Transaction Date'][]=$paymetdetails[$detail->Mid]->TXN_DATE?date('d-m-Y H:i:s',strtotime($paymetdetails[$detail->Mid]->TXN_DATE)):'';
           //$sheet['Bank Ref. No.'][]=$paymetdetails[$detail->Mid]->WALLET_BANK_REF;
           //$sheet['Receipt Mode'][]=$paymetdetails[$detail->Mid]->PG_MODE;
           $sheet['Receipt Amount'][]=number_format($paymetdetails[$detail->Mid]->TXN_AMT,2);
           $sheet['Due Amount'][]=number_format(($detail->LastMonthDue + $detail->CurrentMonthDue + $detail->LatePenalty)-$paid,2);
           $sheet['Type'][]=$detail->Type;
           $sheet['Status'][]=$pay_status;
           }
           $excel->Export($sheet);
          
         }
         if ($details) {
          foreach ($details as $key => $val) {
            //$paymetdetails[$val->Mid] = TXNDETAILS::find()->joinWith(['transactionres','usermser'])->select('SUM(TXN_DETAILS.TXN_AMT) as TXN_AMT,TXN_DETAILS.TXN_DATE,TXN_RESP_DETAILS.WALLET_BANK_REF,TXN_RESP_DETAILS.PG_MODE' )->where(['TXN_DETAILS.USER_ID'=>$val->Mid,'TXN_DETAILS.TXN_STATUS'=>1])->andWhere(['between','DATE_FORMAT(TXN_DETAILS.TXN_DATE,"%Y-%m-%d") as TXN_DATE',$fromdt,$todt])->one();
            $paymetdetails[$val->Mid] = TXNDETAILS::find()->joinWith(['transactionres','usermser'])->select(['SUM(TXN_DETAILS.TXN_AMT) as TXN_AMT','TXN_DETAILS.TXN_DATE','GROUP_CONCAT(TXN_RESP_DETAILS.WALLET_BANK_REF SEPARATOR ",") as WALLET_BANK_REF','GROUP_CONCAT(TXN_RESP_DETAILS.PG_MODE SEPARATOR ",") as PG_MODE'] )->where(['TXN_DETAILS.USER_ID'=>$val->Mid,'TXN_DETAILS.TXN_STATUS'=>1])->andWhere(['between','date(TXN_DETAILS.TXN_DATE)',$fromdt,$todt])->one();
          }
        }
      
       
       return $this->render('customerpayment',['details'=>$details,'paymetdetails'=>$paymetdetails,'pages' => $pages,'branch'=>$branch,'fromdt'=>$fromdt,'todt'=>$todt]);


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

        $details = BranchMaster::find()->where(['IsDelete' => 0]);
       $countQuery = clone $details;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);

      $details = BranchMaster::find()->where(['IsDelete' => 0])->offset($pages->offset)->limit($pages->limit)->all();
      
      return $this->render('branch',['model'=>$model,'details'=>$details,'pages'=>$pages]);

    }
    public function actionExportbranch()
    {
         $spreadsheet = new Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('A1', 'SL NO');
        $sheet->setCellValue('B1', 'BRANCH ID');
        $sheet->setCellValue('C1', 'BRANCH NAME');
        $sheet->setCellValue('D1', 'CLUSTER');
        $sheet->setCellValue('E1', 'STATE');
        $sheet->setCellValue('F1', 'ENTITY');
        
        $rowCount = 2;$i=0;
        $allbranch=BranchMaster::find()->where(['IsDelete' => 0])->all();
        if($allbranch)
        {
            foreach($allbranch as $ak=>$aval)
            {
                $i++;
                $sheet->setCellValue('A' . $rowCount, $i);
                $sheet->setCellValue('B' . $rowCount, $aval->BranchId);
                $sheet->setCellValue('C' . $rowCount, $aval->BranchName);
                $sheet->setCellValue('D' . $rowCount, $aval->Cluster);
                $sheet->setCellValue('E' . $rowCount, $aval->State);
                $sheet->setCellValue('F' . $rowCount, $aval->Entity);
                
                $rowCount++;
            }
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName='Branch Deatils'.date('d-m-Y H:i:s');
        $fileName = $fileName.'.xlsx';
        
        $styleArray = array(
            'font'  => array(
            'bold'  => false,
            'color' => array('rgb' => '337ab7'),
            'size'  => 13,
            'name'  => 'Arial'
            ));
            $sheet->getStyle('A1:F1')->applyFromArray($styleArray);
            $sheet->getParent()->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
    
            foreach(range('A','F') as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            foreach($sheet->getRowDimensions() as $rd) { 
                $rd->setRowHeight(-1); 
            }
            $sheet->getRowDimension(1)->setRowHeight(20);
            $sheet->getRowDimension(2)->setRowHeight(20);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
            
        // Do your stuff here
        ob_end_clean(); ob_start(); 
        $writer->save('php://output');
    }


    public function actionLogout()
    {
   
      Yii::$app->user->logout();
      
      return $this->goHome();
    }
    


  }

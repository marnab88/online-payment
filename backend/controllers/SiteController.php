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
      'actions' => ['login', 'error','subadmin','process','mfi','mfidetails','mfidet','msmedet','previous','msme','adminprev','msmedetails','updatemfi','both','updatemsme','sendsms','mfisms','adminview','delete','url','report','customerdetails','resetpassword','subaddresetpassword','branch','paymentdetails','deleterecord','cust-payment','report2','exportbranch','ajaxrelaodetails'],
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
        $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->one();
        $upload->SmsStatus = 2;
        $upload->save();
       
        return $this->redirect(['msme','id'=>$id,'type'=>$type,'mon'=>$mon,'pagination'=>'show']);
      
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
    Yii::$app->session->setFlash('success','successfully done');

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

 $details = UploadRecords::find()->where(['UploadedBy'=>$uid,'Type'=>$typee,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->all();

}

$details = UploadRecords::find()->where(['UploadedBy'=>$uid,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->all();
return $this->render('home', ['model' => $model, 'MonthYear' => $MonthYear, 'alldetails' => $details]);

}





public function actionAjaxrelaodetails()
{

 $this->layout='blanklayout';

$typee=Yii::$app->user->identity->Type;
$uid=Yii::$app->user->identity->UserId;

if ($typee !="both") {

 $details = UploadRecords::find()->where(['UploadedBy'=>$uid,'Type'=>$typee,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->all();

}

$details = UploadRecords::find()->where(['UploadedBy'=>$uid,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->all();
return $this->render('ajaxrelaodetails', ['alldetails' => $details]);

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

public function actionMfi($id,$type,$mon,$error=false)
{   
 $this->layout = 'common';
 list($month, $year) =explode("/",$mon);
 $month = date("m",strtotime($month));  
 $getid=array();





 if($error){
   $details = ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])
             ->andWhere(['!=','errorMsg','0']);         
  }
  else{
  $details = ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0]);
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
      $sheet['SpouseName'][]=$details->SpouseName;
      $sheet['VillageName'][]=$details->VillageName;
      $sheet['Center'][]=$details->Center;
      $sheet['GroupName'][]=$details->GroupName;
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








 $details = ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0])->all();
 $approve= ExcelData::find()->where(['RecordId'=>$id,'IsApproved'=>1,'IsDelete'=>0])->count();   
 $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
 foreach ($upload as $key => $value) {
 $mon=$value->MonthYear;
 }
/* $command = Yii::$app->db->createCommand("SELECT Eid FROM ExcelData WHERE LoanAccountNo IN (SELECT LoanAccountNo  FROM ExcelData WHERE RecordId='$id'AND IsDelete = 0 GROUP BY LoanAccountNo HAVING COUNT(LoanAccountNo) > 1) and RecordId = '$id'");
 $getallid = $command->queryAll();*/

$getallid=ExcelData::find()->where(['RecordId'=>$id,'IsDelete' => 0]) 
               ->andWhere(['!=','errorCount','0'])  
               ->all();


  $getblankvalue = count($getallid);
 
  foreach ($getallid as $key => $value) {
   $getid[]= $value->Eid;
 }


  $smsstat = UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->one();


/* foreach ($getallid as $key => $value) {
   $getid[]= $value['Eid'];
 }
 foreach ($details as $key => $value) {
  $sms=$value->SmsStatus;
   $paymetdetails = TXNDETAILS::find()->select('SUM(TXN_AMT) as TXN_AMT,TXN_STATUS' )->where(['USER_ID'=>$value->Eid,'month(TXN_DATE)'=>$month,'year(TXN_DATE)' => $year,'TXN_STATUS'=>1,'TYPE'=>'MFI'])->one();
 }*/
 return $this->render('mfi',['details'=>$details, 'id'=>$id, 'approve'=>$approve,'getid'=>$getid,'getblankvalue'=>$getblankvalue,'sms'=>'','type'=>$type,'paymetdetails'=>'','mon'=>$mon,'error'=>$error,'pages'=>$pages,'smsstat'=>$smsstat,]);


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
 $smsstat = UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->one();
 return $this->render('msme',['details'=>$details,'id'=>$id,'approve'=>$approve,'getid'=>$getid,'sms'=>'','getblankvalue'=>$getblankvalue,'type'=>$type,'paymetdetails'=>'','mon'=>$mon,'error'=>$error,'pages'=>$pages,'smsstat'=>$smsstat,]);

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
    $errormsg='';
    $model = ExcelData::find()->where(['Eid'=> $id,'IsDelete'=>0])->one();
    $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
      foreach ($upload as $key => $value) {
       $mon=$value->MonthYear;
      }

    $uid=Yii::$app->user->identity->UserId;
    $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();     
    $details=$model->RecordId;

  if (Yii::$app->request->post('update') == 'update') {
    $BranchName=Yii::$app->request->post()['ExcelData']['BranchName'];
    $Cluster=Yii::$app->request->post()['ExcelData']['Cluster'];
    $state=Yii::$app->request->post()['ExcelData']['State'];
    $ClientId=Yii::$app->request->post()['ExcelData']['ClientId'];
    $LoanAccountNo=Yii::$app->request->post()['ExcelData']['LoanAccountNo'];
    $ClientName=Yii::$app->request->post()['ExcelData']['ClientName'];
    $SpouseName=Yii::$app->request->post()['ExcelData']['SpouseName'];
    $VillageName=Yii::$app->request->post()['ExcelData']['VillageName'];
    $Center=Yii::$app->request->post()['ExcelData']['Center'];
    $GroupName=Yii::$app->request->post()['ExcelData']['GroupName'];
    $MobileNo=Yii::$app->request->post()['ExcelData']['MobileNo'];
    $EmiSrNo=Yii::$app->request->post()['ExcelData']['EmiSrNo'];
    $DemandDate=Yii::$app->request->post()['DemandDate'];
    $LastMonthDue=Yii::$app->request->post()['ExcelData']['LastMonthDue'];
    $CurrentMonthDue=Yii::$app->request->post()['ExcelData']['CurrentMonthDue'];
    $latepenalty=Yii::$app->request->post()['ExcelData']['LatePenalty'];
    $NextInstallmentDate=Yii::$app->request->post()['NextInstallmentDate'];
    $UploadMonth=Yii::$app->request->post()['UploadMonth'];
    $ProductVertical=Yii::$app->request->post()['ExcelData']['ProductVertical'];


    if ($latepenalty =='') {
      $latepenalty = 0;
    }

    $yeararray = explode(" ", $UploadMonth);
    $month=date('M',strtotime($yeararray[0]));
    $year=date('y',strtotime($yeararray[1]));

    $UploadMonth=$month."`".$year;

    $dmddt=date('Y-m-d',strtotime($DemandDate));
    $nxtinst=date('Y-m-d',strtotime($NextInstallmentDate));
   


    $Currentmonth=date('m');
    $Currentyear=date('Y');
    $nextmonth= date('m', strtotime('+1 month'));
    $newdemanddate=date('Y-m',strtotime($DemandDate));

      if (is_numeric($ClientId)) {
        if (strlen($ClientId)>3 && $ClientId>0) {
          $ClientidError=0;
        }else{
          $ClientidError=1;
        }
      }else{
        $ClientidError=(strlen($ClientId)>3)?0:1;
      }
      $ClientNameError =(trim($ClientName)=='')? 0:1;
      $errormoblength=(strlen($MobileNo)!=10)? 1:0;
      $errorEmiSrNo=(is_numeric($EmiSrNo) && ($EmiSrNo>0) && ($EmiSrNo<61))?0:1;
      $LastMonthDueError=(is_numeric($LastMonthDue) && strlen($LastMonthDue)<8)?0:1;
      $CurrentMonthDueError=(is_numeric($CurrentMonthDue) && strlen($CurrentMonthDue)<8)?0:1;
      //$LatePenaltyError=(is_numeric($latepenalty) && strlen($latepenalty)<8)?0:1;
      $LoanAccountNoError=(strlen($LoanAccountNo)>5 && strlen($LoanAccountNo)<10)?0:1;
      $ClusterError =(trim($Cluster)=='')? 1:0;
      $BranchNameError =(trim($BranchName)=='')? 1:0;
      $ClientName1Error =((preg_match("/^[a-zA-Z ]*$/", $ClientName)) && (strlen($ClientName)>3))? 0:1;
      $ProductVerticalError=(trim($ProductVertical)=='MFI')? 1:0;
      /*if (preg_match("/^[a-zA-Z ]*$/", $SpouseName)) {
        echo "yes";
        if (strlen($SpouseName)>3) {
          echo $SpouseNameError=0;

        }else{
          echo $SpouseNameError=1;
        }
      }else{
        echo "no";
      }*/
      //die();
      $SpouseNameError =((preg_match("/^[a-zA-Z ]*$/", $SpouseName)) && (strlen($SpouseName)>3))? 0:1;
   /* echo $SpouseNameError;
    die();*/
      $VillageError =((preg_match("/^[a-zA-Z0-9 -]*$/", $VillageName)) && (strlen($VillageName)>3))? 0:1;
      $CenterError =((preg_match("/^[a-zA-Z0-9 -]*$/", $Center)) && (strlen($Center)>3))? 0:1;
      $GroupError =((preg_match("/^[a-zA-Z0-9 -]*$/", $GroupName)) && (strlen($GroupName)>3))? 0:1;
      
      $NextInstallmentDateError=(date('m',strtotime($NextInstallmentDate)) == $nextmonth && date('Y',strtotime($NextInstallmentDate)) == $Currentyear)?0:1;
      $DemandDateError=((date('m',strtotime($DemandDate)) == $Currentmonth && date('Y',strtotime($DemandDate)) == $Currentyear)|| (date('m',strtotime($DemandDate)) == $nextmonth && date('Y',strtotime($DemandDate)) == $Currentyear) )?0:1;
      $uplodmonthError=((date('m',strtotime($yeararray[0])) == $Currentmonth && date('Y',strtotime($yeararray[1])) == $Currentyear)|| (date('m',strtotime($yeararray[0])) == $nextmonth && date('Y',strtotime($yeararray[1])) == $Currentyear) )?0:1;
      /*$errorclientid=ExcelData::find()->where(['month(DemandDate)'=>date('m',strtotime($dmddt)),'ClientId'=>$ClientId])->count();
        $errorLoanAccountNo =ExcelData::find()->where(['month(DemandDate)'=>date('m',strtotime($dmddt)),'LoanAccountNo'=>$LoanAccountNo])->count();
*/


      $errorclientid=ExcelData::find()
      ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $newdemanddate . '%', false])
      ->andFilterWhere(['ClientId'=>$ClientId])
      ->count();
      

      $errorLoanAccountNo=ExcelData::find()
      ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $newdemanddate . '%', false])
      ->andFilterWhere(['LoanAccountNo'=>$LoanAccountNo])
      ->count();
     
      

      if($SpouseNameError>0)
      $errormsg.='Spouse name is invalid.';

      if($VillageError>0)
        $errormsg.='Village name is invalid.';

      if($CenterError>0)
        $errormsg.='Center name is invalid.';

      if($GroupError>0)
        $errormsg.='Group name is invalid.';

      if($BranchNameError>0)
      $errormsg.='BranchName cannot be empty.';
    
      if($ClusterError>0)
      $errormsg.='cluster cannot be blank.';
    
     /* if($errorclient>1)
      $errormsg.=' duplicate Clientid.';*/
    
      if($ClientidError>0)
      $errormsg.='Client Id is Not Valid. ';
    
      if($LoanAccountNoError>0)
      $errormsg.='Not valid loan account number. ';

      if($errormoblength>0)
      $errormsg.=' not a valid mobile number.';

      if($ClientNameError<1)
      $errormsg.='Client name cant be empty.';

      if($ClientName1Error>0)
      $errormsg.='client name is invalid.';

      if($errorEmiSrNo>0)
      $errormsg.='Emisr must be 1 to 60.';

      if ($DemandDateError>0) 
      $errormsg.='Demand Date must be Current month or next month of current year. ';

      if($errorclientid>1 && ($model->ClientId==$ClientId ))
    $errormsg.='Duplicate Clientid.';

      if($errorclientid>0 && ($model->ClientId!=$ClientId))
      $errormsg.='Duplicate Clientid.';

    //echo $errorclientid.'---'.$model->ClientId.'---'.$ClientId.'<br/>';

    if($errorLoanAccountNo>1 && ($model->LoanAccountNo==$LoanAccountNo))
      $errormsg.='This loan a/c already exist for this month.';

     if($errorLoanAccountNo>0 && ($model->LoanAccountNo!=$LoanAccountNo))
      $errormsg.='This loan a/c already exist for this month.';
//echo $errorLoanAccountNo.'---'.$model->LoanAccountNo.'---'.$LoanAccountNo.'<br/>';echo$errormsg; die();

      if($LastMonthDueError>0)
      $errormsg.='Lastmonth due is not valid.';

      if($CurrentMonthDueError>0)
      $errormsg.='CurrentMonthDue due is not valid.';

     /* if($LatePenaltyError>0)
      $errormsg.='Late Penalty is not valid.';*/

      if ($NextInstallmentDateError>0) 
      $errormsg.='Next Installment Date must be next month of current year. ';

      if ($uplodmonthError>0) 
      $errormsg.='Upload month must be Current month or next month of current year. ';

      if($ProductVerticalError<1)
      $errormsg.='Product Vertical is not valid.';
      /*echo $errormsg;
      die();*/


      $model->BranchName=$BranchName;
        $model->Cluster=$Cluster;
        $model->State=$state;
        $model->ClientId=$ClientId;
        $model->LoanAccountNo=$LoanAccountNo;
        $model->ClientName=$ClientName;
        $model->SpouseName=$SpouseName;
        $model->VillageName=$VillageName;
        $model->Center=$Center;
        $model->GroupName=$GroupName;
        $model->EmiSrNo=$EmiSrNo;
        $model->DemandDate=$dmddt;
        $model->LastMonthDue=$LastMonthDue;
        $model->CurrentMonthDue=$CurrentMonthDue;
        $model->LatePenalty=$latepenalty;
        $model->MobileNo=$MobileNo;
        $model->NextInstallmentDate=$nxtinst;                  
        $model->UploadMonth=$UploadMonth;
        $model->ProductVertical=$ProductVertical;

      if ($model->errorMsg =='' && $model->errorCount==0 && $errormsg=='') {
        $model->errorCount=0;
          $types->Mismatch=$types->Mismatch;
      }
      if ($model->errorMsg !='' && $model->errorCount==1 && $errormsg!='') {
        $model->errorCount=1;
          $types->Mismatch=$types->Mismatch;
      }
      if ($model->errorMsg =='' && $model->errorCount==1 && $errormsg !='') {
        $model->errorCount=1;
          $types->Mismatch=$types->Mismatch+1;
      }
       if ($model->errorMsg =='' && $model->errorCount==0 && $errormsg !='') {
        $model->errorCount=1;
          $types->Mismatch=$types->Mismatch+1;
      }
      if ($model->errorMsg !='' && $model->errorCount==1 && $errormsg =='') {
        $model->errorCount=0;
          $types->Mismatch=$types->Mismatch-1;
      }
     
      //$model->errorCount=$toterror;
      //echo $toterror.'/';
      /*if($errormsg !=''){
      $model->errorCount=1;
      $types->Mismatch=$types->Mismatch;
      if ($model->errorMsg =='') {
       $types->Mismatch=($types->Mismatch)+1;
      }
      if ($types->Mismatch == 0) {
          $types->Mismatch=$types->Mismatch+1;
        }
      }else{
         $model->errorCount=0;
         $types->Mismatch=$types->Mismatch-1;
      }*/

       $model->errorMsg=$errormsg;


      /* $toterror=($errormobile+$errorEmiSrNo+$errorLoanAccountNo+$errormoblength+$BranchNameError+$ClientNameError+$ClientName1Error+$ClusterError+$LastMonthDueError
                +$CurrentMonthDueError+$LoanAccountNoError+$uplodmonthError+$ProductVerticalError+$ClientidError);*/
      /*if ($errormsg !='' && $types->Mismatch == 0) {
       $types->Mismatch=($types->Mismatch)+1;
      }*/
     /* echo $model->errorMsg.'<br/>';
      echo $model->errorCount.'<br/>';
      echo $types->Mismatch;
      
      die();*/
     /* echo $model->errorCount.'/';
      echo $model->errorMsg.'/';*/
          if ($model->save()) {
          /*if ($types->save()) {
            $connection = \Yii::$app->db;
            $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from `ExcelData` WHERE `RecordId`='$types->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
            $result =$mismatchmobile->queryAll();
            $types->MobileCount=$result[0]['cnt'];*/
          //}
          if ($types->save()) {
           Yii::$app->session->setFlash('success', "Account Updated successfully");
           return $this->redirect(['mfi', 'id' => $types->RecordId,'type'=>$type,'mon'=>$mon,'pagination'=>'show']);
         }
      // }
       }
 }     
  /*$details = $model->RecordId;
  $model->errorMsg=null;
  $model->errorCount=0;
  if ($model->load(Yii::$app->request->post()) && $model->save()) {
   $uid=Yii::$app->user->identity->UserId;
   $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();
    
   $connection = \Yii::$app->db;
   $command = $connection->createCommand("SELECT COUNT(*) as cnt FROM `ExcelData` WHERE `RecordId` = '$types->RecordId' and `IsDelete`= 0 GROUP BY LoanAccountNo HAVING cnt > 1 ");
   $result = $command->queryAll(); 
          
   if ($result) {
     $types->Mismatch=0;
   }
   else{
    $types->Mismatch=0 ;
  }*/
  
  /*if ($types->save()) {
    $connection = \Yii::$app->db;
    $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from ExcelData WHERE `RecordId`='$types->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
    $result =$mismatchmobile->queryAll();
    $types->MobileCount=$result[0]['cnt'];
    if ($types->save()){
      Yii::$app->session->setFlash('success', "Account Updated successfully");
      return $this->redirect(['mfi', 'id' => $types->RecordId,'type'=>$type,'mon'=>$mon]);
    }
  }

}*/
return $this->render('mfi_edit',['model'=>$model,'details'=>$details,'type'=>$type,'mon'=>$mon]); 

}



public function actionUpdatemsme($id,$type,$mon){
  $this->layout='common';
  $getallloan=$dupclient=$dupmob=array();
  $errormsg='';
  $model= MsmeExcelData::find()->where(['Mid'=>$id,'IsDelete'=>0])->one();
  $upload=UploadRecords::find()->where(['RecordId'=>$id,'IsDelete'=>0])->all();
        foreach ($upload as $key => $value) {
         $mon=$value->MonthYear;
        }
  $mdeamnddate=date("Y-m-d",strtotime($model->DemandDate));    
  $uid=Yii::$app->user->identity->UserId;

  $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();     
  $details=$model->RecordId;

  if (Yii::$app->request->post('update') == 'update') {
    $BranchName=Yii::$app->request->post()['MsmeExcelData']['BranchName'];
    $Cluster=Yii::$app->request->post()['MsmeExcelData']['Cluster'];
    $state=Yii::$app->request->post()['MsmeExcelData']['State'];
    $ClientId=Yii::$app->request->post()['MsmeExcelData']['ClientId'];
    $LoanAccountNo=Yii::$app->request->post()['MsmeExcelData']['LoanAccountNo'];
    $ClientName=Yii::$app->request->post()['MsmeExcelData']['ClientName'];
    $EmiSrNo=Yii::$app->request->post()['MsmeExcelData']['EmiSrNo'];
    $DemandDate=Yii::$app->request->post()['DemandDate'];
    $LastMonthDue=Yii::$app->request->post()['MsmeExcelData']['LastMonthDue'];
    $CurrentMonthDue=Yii::$app->request->post()['MsmeExcelData']['CurrentMonthDue'];
    $latepenalty=Yii::$app->request->post()['MsmeExcelData']['LatePenalty'];
    $MobileNo=Yii::$app->request->post()['MsmeExcelData']['MobileNo'];
    $NextInstallmentDate=Yii::$app->request->post()['NextInstallmentDate'];
    $UploadMonth=Yii::$app->request->post()['UploadMonth'];
    $ProductVertical=Yii::$app->request->post()['MsmeExcelData']['ProductVertical'];

    $yeararray = explode(" ", $UploadMonth);
    $month=date('M',strtotime($yeararray[0]));
    $year=date('y',strtotime($yeararray[1]));
    $UploadMonth=$month."`".$year;

    $dmddt=date('Y-m-d',strtotime($DemandDate));
    $nxtinst=date('Y-m-d',strtotime($NextInstallmentDate));
    


    $Currentmonth=date('m');
    $Currentyear=date('Y');
    $nextmonth= date('m', strtotime('+1 month'));
    $newdemanddate=date('Y-m',strtotime($DemandDate));


    
    //$DemandDateError=$BranchNameError=$ClusterError=$ClientidError=$LoanAccountNoError=$errormoblength=$ClientNameError=$ClientName1Error=$errorEmiSrNo=$errorclient=$errorLoanAccountNo=$errormobile=$LastMonthDueError=$CurrentMonthDueError=$LatePenaltyError=$NextInstallmentDateError=$uplodmonthError=$ProductVerticalError=0;



      //$errorclient=MsmeExcelData::find()->where(['UploadMonth'=>$model->UploadMonth,'ClientId'=>$ClientId])->count();
      
      //$ClientidError=((preg_match("/^[A-Z]{3}+$/", substr($ClientId, 0, 3))) && (strlen($ClientId)>3))?1:0;
      //$ClientidError=(strlen($ClientId)>3)?0:1;
      //$ClientidError1=(is_numeric($ClientId) && ($ClientId>0))?0:1;
    
      if (is_numeric($ClientId)) {
        if (strlen($ClientId)>3 && $ClientId>0) {
          $ClientidError=0;
        }else{
          $ClientidError=1;
        }
      }else{
        $ClientidError=(strlen($ClientId)>3)?0:1;
      }
      $ClientNameError =(trim($ClientName)=='')? 0:1;
      $errormoblength=(strlen($MobileNo)!=10)? 1:0;
      $errorEmiSrNo=(is_numeric($EmiSrNo) && ($EmiSrNo>0) && ($EmiSrNo<241))?0:1;
      $LastMonthDueError=(is_numeric($LastMonthDue) && strlen($LastMonthDue)<8)?0:1;
      $CurrentMonthDueError=(is_numeric($CurrentMonthDue) && strlen($CurrentMonthDue)<8)?0:1;
      $LatePenaltyError=(is_numeric($latepenalty) && strlen($latepenalty)<8)?0:1;
      $LoanAccountNoError=(strlen($LoanAccountNo)>7)?0:1;
      $ClusterError =(trim($Cluster)=='')? 1:0;
      $BranchNameError =(trim($BranchName)=='')? 1:0;
      $ClientName1Error =((preg_match("/^[a-zA-Z !@#$%^&*)(']*$/", $ClientName)) && (strlen($ClientName)>3))? 0:1;
      $ProductVerticalError=(trim($ProductVertical)=='MSME')? 1:0;
      $NextInstallmentDateError=(date('m',strtotime($NextInstallmentDate)) == $nextmonth && date('Y',strtotime($NextInstallmentDate)) == $Currentyear)?0:1;
      $DemandDateError=((date('m',strtotime($DemandDate)) == $Currentmonth && date('Y',strtotime($DemandDate)) == $Currentyear)|| (date('m',strtotime($DemandDate)) == $nextmonth && date('Y',strtotime($DemandDate)) == $Currentyear) )?0:1;
      $uplodmonthError=((date('m',strtotime($yeararray[0])) == $Currentmonth && date('Y',strtotime($yeararray[1])) == $Currentyear)|| (date('m',strtotime($yeararray[0])) == $nextmonth && date('Y',strtotime($yeararray[1])) == $Currentyear) )?0:1;
      
      //SELECT * FROM MsmeExcelData WHERE ClientId IN (SELECT ClientId FROM MsmeExcelData WHERE date_format(`DemandDate`, "%Y-%m")='2020-05' GROUP BY ClientId HAVING COUNT(ClientId) > 1) and date_format(`DemandDate`, "%Y-%m")='2020-05'
      //SELECT * FROM MsmeExcelData WHERE MobileNo IN (SELECT MobileNo FROM MsmeExcelData WHERE date_format(`DemandDate`, "%Y-%m")='2020-05' GROUP BY MobileNo HAVING COUNT(ClientId) > 1) and date_format(`DemandDate`, "%Y-%m")='2020-05'
      /*$commandclint = Yii::$app->db->createCommand("SELECT ClientId FROM MsmeExcelData WHERE ClientId IN (SELECT ClientId  FROM MsmeExcelData WHERE date_format(`DemandDate`, '%Y-%m')='$newdemanddate' GROUP BY ClientId HAVING COUNT(ClientId) > 1) and date_format(`DemandDate`, '%Y-%m')='$newdemanddate'");      
       $getallclientid = $commandclint->queryAll();
      foreach ($getallclientid as $key => $val) {
         $dupclient[]= $val['ClientId'];
       }
       var_dump($dupclient);
       echo $ClientId;
     if (in_array("$ClientId", array_values($dupclient))){$errormsg.='Duplicate Clientid.';}



     $commandmob = Yii::$app->db->createCommand("SELECT MobileNo FROM MsmeExcelData WHERE MobileNo IN (SELECT MobileNo  FROM MsmeExcelData WHERE date_format(`DemandDate`, '%Y-%m')='$newdemanddate' GROUP BY MobileNo HAVING COUNT(MobileNo) > 1) and date_format(`DemandDate`, '%Y-%m')='$newdemanddate'");     
     $getalldupmob = $commandmob->queryAll();
    foreach ($getalldupmob as $key => $vall) {
       $dupmob[]= $vall['LoanAccountNo'];
     }

     if (in_array("$MobileNo", array_values($dupmob))){$errormsg.='this mobile number is available for this month.';}




     $command = Yii::$app->db->createCommand("SELECT LoanAccountNo FROM MsmeExcelData WHERE LoanAccountNo IN (SELECT LoanAccountNo  FROM MsmeExcelData WHERE date_format(`DemandDate`, '%Y-%m')='$newdemanddate' GROUP BY LoanAccountNo HAVING COUNT(LoanAccountNo) > 1) and date_format(`DemandDate`, '%Y-%m')='$newdemanddate'");    
     $getallid = $command->queryAll();
    foreach ($getallid as $key => $value) {
       $getallloan[]= $value['LoanAccountNo'];
     }

     if (in_array("$LoanAccountNo", array_values($getallloan))){$errormsg.='This loan a/c already exist for this month.';}*/
    


      $errorclient=MsmeExcelData::find()
      ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $newdemanddate . '%', false])
      ->andFilterWhere(['ClientId'=>$ClientId])
      ->count();

      $errormobile=MsmeExcelData::find()
      ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $newdemanddate . '%', false])
      ->andFilterWhere(['MobileNo'=>$MobileNo])
      ->count();
      

      $errorLoanAccountNo=MsmeExcelData::find()
      ->where(['like', 'date_format(`DemandDate`, "%Y-%m")', $newdemanddate . '%', false])
      ->andFilterWhere(['LoanAccountNo'=>$LoanAccountNo])
      ->count();
      

      
      
      //$errorclient=$errormobile=$errorLoanAccountNo=0;
       if ($DemandDateError>0) 
      $errormsg.='Demand Date must be Current month or next month of current year. ';
   

      if($BranchNameError>0)
      $errormsg.='BranchName cannot be empty.';
    
      if($ClusterError>0)
      $errormsg.='cluster cannot be blank.';
    
      if($ClientidError>0)
      $errormsg.='Client Id is Not Valid. ';

      if($LoanAccountNoError>0)
      $errormsg.='Not valid loan account number. ';

      if($errormoblength>0)
      $errormsg.=' not a valid mobile number.';

      if($ClientNameError<1)
      $errormsg.='Client name cant be empty.';

      if($ClientName1Error>0)
      $errormsg.='client name is invalid.';

      if($errorEmiSrNo>0)
      $errormsg.='Emisr must be 1 to 240.';

      if ($model->ClientId==$ClientId) {
        # code...
      }

    if($errorclient>1 && ($model->ClientId==$ClientId ))
    $errormsg.='Duplicate Clientid.';

      if($errorclient>0 && ($model->ClientId!=$ClientId))
      $errormsg.='Duplicate Clientid.';

//echo $errorclient.'---'.$model->ClientId.'---'.$ClientId.'<br/>';
      

     if($errorLoanAccountNo>1 && ($model->LoanAccountNo==$LoanAccountNo))
      $errormsg.='This loan a/c already exist for this month.';

     if($errorLoanAccountNo>0 && ($model->LoanAccountNo!=$LoanAccountNo))
      $errormsg.='This loan a/c already exist for this month.';
    
//echo $errorLoanAccountNo.'---'.$model->LoanAccountNo.'---'.$LoanAccountNo.'<br/>';

      if($errormobile>1 && ($model->MobileNo==$MobileNo))
      $errormsg.='this mobile number is available for this month.';

     if($errormobile>0 && ($model->MobileNo!=$MobileNo))
      $errormsg.='this mobile number is available for this month.';
  
//echo $errormobile.'---'.$model->MobileNo.'---'.$MobileNo.'<br/>';

      if($LastMonthDueError>0)
      $errormsg.='Lastmonth due is not valid.';

      if($CurrentMonthDueError>0)
      $errormsg.='CurrentMonthDue due is not valid.';

      if($LatePenaltyError>0)
      $errormsg.='Late Penalty is not valid.';

      if ($NextInstallmentDateError>0) 
      $errormsg.='Next Installment Date must be next month of current year. ';

      if ($uplodmonthError>0) 
      $errormsg.='Upload month must be Current month or next month of current year. ';

      if($ProductVerticalError<1)
      $errormsg.='Product Vertical is not valid.';
//echo $errormsg;
    // die();
    
    /*echo $errorclient.'--'.$errormobile.'-----'. $errorLoanAccountNo;
    echo $errormsg;
    die();*/

   /* $toterror=($errormobile+$LatePenaltyError+$errorEmiSrNo+$errorLoanAccountNo+$errormoblength+$BranchNameError+$ClientNameError+$ClientName1Error+$ClusterError+$LastMonthDueError
                +$CurrentMonthDueError+$LoanAccountNoError+$uplodmonthError+$ProductVerticalError+$ClientidError);*/

        $model->BranchName=$BranchName;
        $model->Cluster=$Cluster;
        $model->State=$state;
        $model->ClientId=$ClientId;
        $model->LoanAccountNo=$LoanAccountNo;
        $model->ClientName=$ClientName;
        $model->EmiSrNo=$EmiSrNo;
        $model->DemandDate=$dmddt;
        $model->LastMonthDue=$LastMonthDue;
        $model->CurrentMonthDue=$CurrentMonthDue;
        $model->LatePenalty=$latepenalty;
        $model->MobileNo=$MobileNo;
        $model->NextInstallmentDate=$nxtinst;                  
        $model->UploadMonth=$UploadMonth;
        $model->ProductVertical=$ProductVertical;


    
       if ($model->errorMsg =='' && $model->errorCount==0 && $errormsg=='') {
        $model->errorCount=0;
          $types->Mismatch=$types->Mismatch;
      }
      if ($model->errorMsg !='' && $model->errorCount==1 && $errormsg!='') {
        $model->errorCount=1;
          $types->Mismatch=$types->Mismatch;
      }
      if ($model->errorMsg =='' && $model->errorCount==1 && $errormsg !='') {
        $model->errorCount=1;
          $types->Mismatch=$types->Mismatch+1;
      }
       if ($model->errorMsg =='' && $model->errorCount==0 && $errormsg !='') {
        $model->errorCount=1;
          $types->Mismatch=$types->Mismatch+1;
      }
      if ($model->errorMsg !='' && $model->errorCount==1 && $errormsg =='') {
        $model->errorCount=0;
          $types->Mismatch=$types->Mismatch-1;
      }




/*
      if ($types->Mismatch == 0) {
          $types->Mismatch=($types->Mismatch)+1;
        }
      if($errormsg !=''){
      $model->errorCount=1;
      $types->Mismatch=$types->Mismatch;
      }else{
         $model->errorCount=0;
         $types->Mismatch=$types->Mismatch-1;
      }*/
     

     /* if ($errormsg !='' && $model->errorMsg =='') {
       $types->Mismatch=($types->Mismatch)+1;
      }

       if ($errormsg !='' && $types->Mismatch == 0) {
          $types->Mismatch=($types->Mismatch)+1;
        }*/
//die();
        //if ($errormsg != '') {
          $model->errorMsg=$errormsg;
        /*}else{
          $model->errorMsg='NULL';
        }*/

       

      /* $toterror=($errormobile+$LatePenaltyError+$errorEmiSrNo+$errorLoanAccountNo+$errormoblength+$BranchNameError+$ClientNameError+$ClientName1Error+$ClusterError+$LastMonthDueError
                +$CurrentMonthDueError+$LoanAccountNoError+$uplodmonthError+$ProductVerticalError+$ClientidError);*/
    /*  if ($errormsg !='' && $types->Mismatch == 0) {
       $types->Mismatch=($types->Mismatch)+1;
      }*/
     /* echo $model->errorMsg.'<br/>';
      echo $model->errorCount.'<br/>';
      echo $types->Mismatch;
      
      die();*/
     /* echo $model->errorCount.'/';
      echo $model->errorMsg.'/';*/
          if ($model->save()) {
          /*$uid=Yii::$app->user->identity->UserId;

          $types=UploadRecords::find()->where(['UploadedBy'=>$uid,'RecordId'=>$model->RecordId,'IsDelete' => 0])->orderBy(['RecordId'=>SORT_DESC])->one();
          $connection=\Yii::$app->db;
          $command=$connection->createCommand("SELECT COUNT(*) as cnt FROM `MsmeExcelData` WHERE `RecordId` = '$types->RecordId' and `IsDelete`= 0 GROUP BY LoanAccountNo HAVING cnt > 1 ");
          $result = $command->queryAll();
          //$types->Mismatch;
          if ($result) {
            echo "hiiii";
             $types->Mismatch=$result[0]['cnt'];
             echo $types->Mismatch.'-----------------';
          }
          else{
            echo "hello";
            if ($model->errorCount=0) {
              $types->Mismatch=$types->Mismatch-1;
            }else{
             $types->Mismatch=0;
           }
           echo $types->Mismatch.'========';
          }
          die();*/
       /*   if ($types->save()) {
            $connection = \Yii::$app->db;
            $mismatchmobile=$connection->createCommand("SELECT COUNT(*) as cnt from `MsmeExcelData` WHERE `RecordId`='$types->RecordId' and  `IsDelete`=0 and length(`MobileNo`) != 10");
            $result =$mismatchmobile->queryAll();
            $types->MobileCount=$result[0]['cnt'];
          }*/
          if ($types->save()) {
           Yii::$app->session->setFlash('success', "Account Updated successfully");
           return $this->redirect(['msme', 'id' => $types->RecordId,'type'=>$type,'mon'=>$mon,'pagination'=>'show']);
         }
       }
 }
 return $this->render('msme_edit',['model'=>$model,'details'=>$details,'type'=>$type,'mon'=>$mon]);
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
       //$lnacno='';

        $fromdate=date('Y-m-01');
          $todate=date('Y-m-d', strtotime(date('Y-m-d'). ' +1 day'));
          $ttdate=date('Y-m-d');
          $frmdate=date('d-m-Y',strtotime($fromdate));
          $tdate=date('d-m-Y',strtotime($ttdate));

          $getpayment = TXNDETAILS::find();
       /*if (isset(Yii::$app->request->post()['search'])) {
          $lnacno = Yii::$app->request->get('loanacno');*/
          $lnacno=isset(Yii::$app->request->get()['loanacno'])?Yii::$app->request->get()['loanacno']:'';
          $status=isset(Yii::$app->request->get()['status'])?Yii::$app->request->get()['status']:'';
          $fromdate=isset(Yii::$app->request->get()['fromdate'])?Yii::$app->request->get()['fromdate']:$fromdate;
          $todate=isset(Yii::$app->request->get()['todate'])?Yii::$app->request->get()['todate']:$todate;

           if (isset(Yii::$app->request->get()['fromdate'])) {
            $frmdate=date('d-m-Y',strtotime($fromdate));
          }
          
          if (isset(Yii::$app->request->get()['todate'])) {
            $tdate=date('d-m-Y',strtotime($todate));
          }
           


          
           $fromdate=date('Y-m-d',strtotime($fromdate));
            $todate=date('Y-m-d', strtotime(date('Y-m-d').'+1day'));
          
       //}

           
            $getpayment=$getpayment->Where(['between','TXN_DATE',$fromdate,$todate]);
             if ($lnacno) {
              $getpayment=$getpayment->andWhere(['LOAN_ID'=>$lnacno]);
            }
            if ($status >= 0 && $status !='') {
              $getpayment=$getpayment->andWhere(['TXN_STATUS'=>$status]);
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
               $sheet['Mobile No'][]=$value->MOB_NO;
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

      return $this->render('paymentdetails',['getallreport'=>$getallreport,'status'=>$status,'lnacno'=>$lnacno,'pages'=>$pages,'frmdate'=>$frmdate,'tdate'=>$tdate,'fromdate'=>$fromdate,'todate'=>$todate]);

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
          $fromdate=date('Y-m-01');
          $todate=date('Y-m-d', strtotime(date('Y-m-d'). ' +1 day'));
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

          $allcustomer = $mod->find()
          ->select(['RecordId','BranchName','Cluster','State','ProductVertical','DemandDate','count(ClientId) as totalcustomer'])
          ->where(['IsDelete' => 0])
          ->groupBy(['BranchName']);
          $branchpayment=new MsmeExcelData();


          //if (isset(Yii::$app->request->get()['report_search'])) {
            /*$fromdate=Yii::$app->request->get()['fromdate']?Yii::$app->request->get()['fromdate']:$fromdate;
            $todate=Yii::$app->request->get()['todate']?Yii::$app->request->get()['todate']:$todate;
            $type=Yii::$app->request->get()['type']?Yii::$app->request->get()['type']:'';
            $branchname=Yii::$app->request->get()['branchname']?Yii::$app->request->get()['branchname']:'';*/

           /* $frmdate=date('d-m-Y',strtotime($fromdate));
            $tdate=date('d-m-Y',strtotime($todate));*/
           /* if($fromdt)
            {
             $allcustomer=$allcustomer->andWhere(['between','OnDate',$fromdt,$todt]);
            }*/
            if ($branchname) {
              $allcustomer=$allcustomer->andWhere(['BranchName'=>$branchname]);
            }
            $fromdate=date('Y-m-d', strtotime($fromdate));
            $todate=date('Y-m-d', strtotime($todate. ' +1 day'));  
       // }
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

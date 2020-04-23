<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
$today=date('dmYHis');
$this->title = 'PAYMENTINVOICE'.$today;
?>
<style>
     .table td:first-child{font-weight:bold;font-size: 11px;}
     .table td:nth-child(2){color:#0A62BA;font-size: 11px;}
     .page{box-shadow: 0 .5rem 1rem rgba(0,0,0,.0)!important;}
     .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding: 2px;
    line-height: normal;
    vertical-align: top;
    border: 0px;
}
tr.highlight {
    border-top: 6px solid;
    border-bottom: 6px solid;
    border-color: transparent;
}
.log{ width: 300px;}
     @media print{
        .logout{
            display: none;
        }
     }

</style>


<body>
    <div class="container" style="background-color:#fff;">
       
            <div class="row align-items-center">
                <div class="col-md-4 col-md-offset-4 page" style=" padding-top:0px; margin-top:25px; padding-top:20px;">
                    <div class="log">
                    <?php if ($pre->TYPE == 'MSME') {?>
                        <p style="text-align: center;" ><img src="<?= Yii::getAlias('@frontendUrl')?>/images/logo1.jpg" height="40"/></p>
                   <?php } else{?>
                   <p><img src="<?= Yii::getAlias('@frontendUrl')?>/images/logo2.jpg" height="50"/></p>
                   <?php }?>
                    </div>
                    <table class="table" style="width:300px;">
                        <tr>
                            <td>Date:</td>
                            <td style="text-align:right;"><?= $date ?></td>
                        </tr>
                        <tr>
                            <td>Receipt No</td>
                            <td style="text-align:right;"><?=$pre->transactionres->WALLET_BANK_REF?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td style="text-align:right;"><?= $details->BranchName ?></td>
                        </tr>
                         <?php if ($pre->TYPE == 'MFI') {?>
                        <tr>
                            <td>Village</td>
                            <td style="text-align:right;"><?= $details->VillageName ?></td>
                        </tr>
                        <tr>
                            <td>Center</td>
                            <td style="text-align:right;"><?= $details->Center ?></td>
                        </tr>
                        <tr>
                            <td>Group </td>
                            <td style="text-align:right;"><?= $details->GroupName ?></td>
                        </tr>
                        <?php } else{?>
                        <?php echo ""; ?>
                         <?php }?>
                        <tr>
                            <td>Customer Name</td>
                            <td style="text-align:right;"><?= $details->ClientName ?></td>
                        </tr>
                        <tr>
                            <td>Mobile No </td>
                            <td style="text-align:right;"><?=$details->MobileNo?></td>
                        </tr>
                        <tr>
                            <td>Loan Account NO </td>
                            <td style="text-align:right;"><?=$pre->LOAN_ID?></td>
                        </tr>
                        <tr>
                            <td>Demand Date</td>
                            <td style="text-align:right;"><?=DATE('d-m-Y',strtotime($pre->TXN_DATE))?></td>
                        </tr>
                        <tr>
                            <td>Demand Amount</td>
                            <td style="text-align:right;"><?= $details->CurrentMonthDue + $details->LatePenalty+$details->LastMonthDue ?></td>
                        </tr>
                         <tr>
                            <td>Previously collected Amount</td>
                            <td style="text-align:right;"><?= $pre_pre->TXN_AMT ?></td>
                        </tr>
                        
                        <tr>
                            <?php if ($pre->TYPE == 'MSME') {?>
                                <td>Receipt Amount</td>
                            <?php }else{ ?>
                                <td>Collection Amount</td>
                            <?php } ?>
                            <td style="text-align:right;">
                                    <?=$pre->TXN_AMT?>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Transaction Mode</td>
                            <td style="text-align:right;">
                                <?php if ($pre->TXN_STATUS == 1 ) { echo "SUCCESS";}else{ echo "FAILURE";}?>
                            </td>
                        </tr>
                        <tr>
                            <td>Receipt Mode</td>
                            <td style="text-align:right;"><?=$pre->transactionres->PG_MODE?></td>
                        </tr>
                        <tr>
                            <td>Due Amount ( If any ) </td>
                            <td style="text-align:right; color:red;"><?= $details->CurrentMonthDue + $details->LatePenalty+$details->LastMonthDue-$pre_pre->TXN_AMT-$pre->TXN_AMT ?></td>
                        </tr>
                        <tr>
                            <td>Next Installment Date </td>
                            <td style="text-align:right;"><?=DATE('d-m-Y',strtotime($details->NextInstallmentDate))?></td>
                        </tr>
                        <tr class="highlight">
                            <td colspan="2" style="font-size:11px; text-align: justify; margin-bottom: 6px; margin-top: 6px; line-height:normal;">Please quote your  Receipt no  for any queries relating to this transaction .   The receipt is electronically generated and no signature is required.</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:12px; text-align: left;">CLIENT GRIEVANCE REDRESSAL TOLLFREE NUMBER: <span style="color:#0A62BA;">18008437200</span>  </td>
                            
                        </tr>
                    </table>
                </div>
            </div>
      
    </div>
</body>

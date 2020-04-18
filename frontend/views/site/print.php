<?php
use yii\helpers\Url;
/* @var $this yii\web\View */
$today=date('dmYHis');
$this->title = 'PAYMENTINVOICE'.$today;
?>
<style>
     .table td:first-child{font-weight:bold;}
     .table td:nth-child(2){color:#0A62BA;}
     @media print{
        .logout{
            display: none;
        }
     }
</style>


<body>
    <div class="container-fluid" style="background-color:#fff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 page" style=" padding-top:50px;">
                    <?php if ($pre->TYPE == 'MSME') {?>
                        <p><img src="<?= Yii::getAlias('@frontendUrl')?>/images/logo1.jpg" height="70"/></p>
                   <?php } else{?>
                   <p><img src="<?= Yii::getAlias('@frontendUrl')?>/images/logo2.jpg" height="70"/></p>
                   <?php }?>
                    
                    <table class="table table-striped" style="width:400px;border:1px solid #dedede;" align="center">
                        <tr>
                            <td>Date</td>
                            <td style="text-align:center;"><?= $date ?></td>
                        </tr>
                        <tr>
                            <td>Receipt No</td>
                            <td style="text-align:center;"><?=$pre->LOAN_ID?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td style="text-align:center;"><?= $details->BranchName ?></td>
                        </tr>
                         <?php if ($pre->TYPE == 'MFI') {?>
                        <tr>
                            <td>Village</td>
                            <td style="text-align:center;"><?= $details->VillageName ?></td>
                        </tr>
                        <tr>
                            <td>Center</td>
                            <td style="text-align:center;"><?= $details->Center ?></td>
                        </tr>
                        <tr>
                            <td>Group </td>
                            <td style="text-align:center;"><?= $details->GroupName ?></td>
                        </tr>
                        <?php } else{?>
                        <?php echo ""; ?>
                         <?php }?>
                        <tr>
                            <td>Customer Name</td>
                            <td style="text-align:center;"><?= $details->ClientName ?></td>
                        </tr>
                        <tr>
                            <td>Mobile No </td>
                            <td style="text-align:center;"><?=$details->MobileNo?></td>
                        </tr>
                        <tr>
                            <td>Loan Account NO </td>
                            <td style="text-align:center;"><?=$pre->LOAN_ID?></td>
                        </tr>
                        <tr>
                            <td>Demand Date</td>
                            <td style="text-align:center;"><?=DATE('d-m-Y',strtotime($pre->TXN_DATE))?></td>
                        </tr>
                        <tr>
                            <td>Demand Amount</td>
                            <td style="text-align:center;"><?= $details->CurrentMonthDue + $details->LatePenalty ?></td>
                        </tr>
                        <tr>
                            <?php if ($pre->TYPE == 'MSME') {?>
                                <td>Receipt Amount</td>
                            <?php }else{ ?>
                                <td>Collection Amount</td>
                            <?php } ?>
                            <td style="text-align:center;">
                                    <?=$pre->TXN_AMT?>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Transaction Mode</td>
                            <td style="text-align:center;">
                                <?php if ($pre->TXN_STATUS == 1 ) { echo "SUCCESS";}else{ echo "FAILURE";}?>
                            </td>
                        </tr>
                        <tr>
                            <td>Receipt Mode</td>
                            <td style="text-align:center;"><?=$pre->PAYMENT_TYPE?></td>
                        </tr>
                        <tr>
                            <td>Due Amount ( If any ) </td>
                            <td style="text-align:center; color:red;"><?= $details->CurrentMonthDue + $details->LatePenalty-$pre->TXN_AMT ?></td>
                        </tr>
                        <tr>
                            <td>Next Installment Date </td>
                            <td style="text-align:center;"><?=DATE('d-m-Y',strtotime($details->NextInstallmentDate))?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size:11px;">Please quote your  Receipt no  for any queries relating to this transaction .   The receipt is electronically generated and no signature is required.</td>
                        </tr>
                        <tr>
                            <td colspan="2">CLIENT GRIEVANCE REDRESSAL TOLLFREE NUMBER<br/><span style="color:#0A62BA;">18008437200</span>  </td>
                            
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<?php

namespace common\models;

use Yii;
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
/**
 * This is the model class for table "Records".
 *
 * @property int $SlNo
 * @property string $UserName
 * @property string $UserId
 * @property int $MobileNo
 * @property int $Amount
 * @property string $DueDate
 * @property string $PaymentLink
 * @property int $SmsStatus
 * @property int $WhatsappStatus
 * @property int $PaymentStatus
 * @property string $OnDate
 * @property string $UpdatedDate
 * @property int $IsDelete
 */
class ExportExcel extends Model
{
    public function Export($write,$filename='download'){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $column='A';
        $row=0;
        foreach($write as $head=>$value){
            $sheet->setCellValue($column.'1', $head);
            foreach($value as $key=>$val){
                $row=$key+2;
                //echo $column.$row.'--'.$val.'<br>';
                
                $sheet->setCellValue($column.$row, $val);
            }
            $column++;
        }
        $fileconcat=date('Y_m_d');
        //$sheet->setCellValue('A1', 'Hello World !');
        //$sheet->getStyle('A1:'.$column.'1')->getFont()->setBold(true);
       // $sheet->getStyle('A1:'.$column.'1')->setHeight(36);
       $sheet->getRowDimension('1')->setRowHeight(50);
       $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ];
       foreach(range('A',$column) as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $sheet->getStyle('A1:'.$column.'1')->applyFromArray($styleArray);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.$fileconcat.'.xlsx"');
            header('Cache-Control: max-age=0');
            
            // Do your stuff here
            //$writer = \PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel2007');
            ob_end_clean(); ob_start(); 
        $writer->save('php://output');
        die;
    }
}

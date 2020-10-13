<?php
/**
 * Free Loan Letter PDF Generator
 * Copyright (C) 2019  Drajat Hasan 2017 (drajathasan20@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */
define('INDEX_AUTH', '1');

require '../../../sysconfig.inc.php';
require LIB.'fpdf/fpdfpp.php';
require SB.'admin/default/session.inc.php';
require SB.'admin/default/session_check.inc.php';

// Function to show number on letter
function counter(){
$handle = fopen('../../../files/freeloan/loanquee.txt', "r");
  if(!$handle){
    utility::jsAlert(__('File Not Found!'));
  } else {
    $counter = (int) fread($handle,20);
    fclose ($handle);
    $counter++;
    $handle = fopen('../../../files/freeloan/loanquee.txt', "w");
    fwrite($handle,$counter) ;
    fclose ($handle) ;  
  }
}

// include printed settings configuration file
include SB.'admin'.DS.'admin_template'.DS.'printed_settings.inc.php';
// load print settings from database to override value from printed_settings file
loadPrintSettings($dbs, 'freeloan');

// local date format
$sysconf['month'] = date('n');
$sysconf['array_ina_month_format'] =  array('1' => 'Januari','2' => 'Februari','3' => 'Maret','4' => 'April','5' => 'Mei','6' => 'Juni','7' => 'Juli', '8' => 'Agustus','9' => 'September','10' => 'Oktober','11' => 'November','12' => 'Desember');
$sysconf['month_full'] = date('d')." ".$sysconf['array_ina_month_format'][$sysconf['month']]." ".date("Y");

// check if label session array is available
if (!isset($_SESSION['fll'])) {
    utility::jsAlert(__('There is no data to print!'));
    die();
}
if (count($_SESSION['fll']) < 1) {
    utility::jsAlert(__('There is no data to print!'));
    die();
}
// concat all ID together
$member_ids = '';
foreach ($_SESSION['fll'] as $id) {
    $member_ids .= '\''.$id.'\',';
}
// strip the last comma
$member_ids = substr_replace($member_ids, '', -1);

$member_q = $dbs->query('SELECT m.member_essay, m.member_name, m.member_id, m.member_image, m.member_address, m.member_email, m.inst_name, m.postal_code, m.pin, m.member_phone, m.expire_date, m.register_date, mt.member_type_name FROM member AS m
    LEFT JOIN mst_member_type AS mt ON m.member_type_id=mt.member_type_id
    WHERE m.member_id IN('.$member_ids.')');
$member_datas = array();
while ($member_d = $member_q->fetch_assoc()) {
    if ($member_d['member_id']) {
        $member_datas[] = $member_d;
    }
}

$show = fopen(SB.'files/freeloan/loanquee.txt', "r");
if (!$show) {
	utility::jsAlert(__('Can not read file! loanquee.txt'));
	exit();
}

// chunk cards array
$chunked_card_arrays = array_chunk($member_datas, $sysconf['print']['freeloan']['items_per_row']);

$pdf = new PDFPP();
foreach ($chunked_card_arrays as $membercard_rows) {
	foreach ($membercard_rows as $card) {
		// Get num
		$number            = file_get_contents(SB.'files/freeloan/loanquee.txt');
		if ($number == 0) {
			$number = $number + 1;
		}
		$sysconf['number'] = sprintf("%03d", $number);
		/* Add page*/
		$pdf->AddPage();
		/* Header */
		$pdf->Image(SB.'files'.DS.'freeloan'.DS.'.$sysconf['print']['freeloan']['logo_surat'], 27, 10,-180);
		$pdf->Ln(35);
		$pdf->SetFont('Arial','U',14);
		$pdf->Cell(190,3, $sysconf['print']['freeloan']['caption_letter'],0,1,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(190,10,'Nomor : '.$sysconf['number'].$sysconf['print']['freeloan']['number_format'].$sysconf['print']['freeloan']['institute']."/".$sysconf['print']['freeloan']['period']."/".$sysconf['print']['freeloan']['year'], 0,1,'C');
		$pdf->SetFont('Arial','',12);
		$pdf->Ln(10);
		/* Content */
		$pdf->Cell(23,5,'',0);
		$pdf->Cell(190,3, $sysconf['print']['freeloan']['declare_letter'] ,0,1,'L');
		$pdf->Ln();
		$pdf->Cell(25,5,'',0);
		$pdf->Cell(30,10,'Nama', 0, '', 'L');
		$pdf->Cell(5,10,':',0, '', 'C');
		$pdf->Cell(120,10,$card['member_name'], 0, '', 'L');
		$pdf->Ln();
		$pdf->Cell(25,5,'',0);
		$pdf->Cell(30,10,'NIM', 0, '', 'L');
		$pdf->Cell(5,10,':',0, '', 'C');
		$pdf->Cell(120,10,$card['member_id'], 0, '', 'L');
		$pdf->Ln();
		$pdf->Cell(25,5,'',0);
		$pdf->Cell(30,10,'Kelas', 0, '', 'L');
		$pdf->Cell(5,10,':',0, '', 'C');
		$pdf->Cell(120,10,$card['inst_name'], 0, '', 'L');
		$pdf->Ln(13);
		$pdf->Cell(24,5,'',0);
		$pdf->MultiCell(145,6,$sysconf['print']['freeloan']['result_letter'],0);
		$pdf->Ln(13);
		/* Signature */
		$pdf->Cell(120,5,'',0);
		$pdf->Cell(70,5,$sysconf['print']['freeloan']['city'].', '.$sysconf['month_full'],0,1,'L');
		$pdf->Cell(120,5,'',0);
		$pdf->Cell(70,5,$sysconf['print']['freeloan']['division_of_signature'],0,1,'L');
		$pdf->Ln(20);
		$pdf->Cell(120,5,'',0);
		$pdf->SetFont('Arial','U',12);
		$pdf->Cell(70,5,$sysconf['print']['freeloan']['name_of_signature'],0,1,'L');
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(120,5,'',0);
		$pdf->Cell(70,5,$sysconf['print']['freeloan']['id_of_signature'],0,1,'L');
		// add next num
		$next_num = $number + 1;
		file_put_contents(SB.'files/freeloan/loanquee.txt', $next_num);
	}
}
// Auto print
if ($sysconf['print']['freeloan']['autoprint']) {
	$pdf->AutoPrint();
}
$pdf->Output();
unset($_SESSION['fll']);
exit();

<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/

$this->headers = array(_('Sl.No.'),	_('Decription'), _('Kms'), _('Hrs.'),
		_('Rate'), _('Amount'));

$this->row = $this->pageHeight - $this->topMargin;

$upper = $this->row - 2 * $this->lineHeight;
$lower = $this->bottomMargin + 8 * $this->lineHeight;


$right = $this->pageWidth - $this->rightMargin;
$left = $this->leftMargin;
$width = ($right - $this->leftMargin) / 5;

$icol = $this->pageWidth / 2;
$ccol = $this->cols[0] + 4;
$c2col = $ccol + 60;
$ccol2 = $icol / 2;
$mcol = $icol + 8;
$mcol2 = $this->pageWidth - $ccol2;
$cols = count($this->cols);

//horizontal line points
$hline1 = $upper;
$hline2 = $hline1 - 1.5 * $this->lineHeight;
$hline3 = $hline2 - 20 * $this->lineHeight;
$hline4 = $hline3 - 2 * $this->lineHeight;
$hline5 = $hline4 - 15.5 * $this->lineHeight;
$hline6 = $hline5 - 2 * $this->lineHeight;
$hline7 = $hline6 - 2 * $this->lineHeight;
$hline8 = $hline7 - 2 * $this->lineHeight;
$hline9 = $hline8 - 2 * $this->lineHeight;
$hline10 = $hline9 - 2 * $this->lineHeight;
$hline11 = $hline10 - 2 * $this->lineHeight;
$hline12 = $hline11 - 2 * $this->lineHeight;
$hline13 = $hline12 - 4.5 * $this->lineHeight;
$hline14 = $this->bottomMargin;

//$this->Line($hline1);
//$this->Line($hline2);
$this->Line($hline3);
$this->Line($hline4);
$this->Line($hline5);
$this->Line($hline6);
$this->Line($hline7);
$this->Line($hline8);

$this->Line1($hline9,0,$this->cols[2],$left);
$this->Line1($hline10,0,$this->cols[2],$left);
$this->Line1($hline11,0,$this->cols[2],$left);
$this->Line1($hline12,0,$this->cols[2],$left);


$this->Line($hline13);
$this->Line($hline14);

$this->LineTo($right, $hline3 ,$right, $hline14);
$this->LineTo($left, $hline3 ,$left, $hline14);

for ($i = 1; $i < count($this->headers); $i++)
{
	if($i == count($this->headers)-1)
		$this->LineTo($this->cols[$i], $hline3,$this->cols[$i], $hline7);
	else
		$this->LineTo($this->cols[$i], $hline3,$this->cols[$i], $hline5);
}

$tax_n_pan_col = $this->cols[1]+70;
$this->LineTo($tax_n_pan_col, $hline9 ,$tax_n_pan_col, $hline10);
$this->LineTo($tax_n_pan_col, $hline11 ,$tax_n_pan_col, $hline12);

$this->LineTo($this->cols[2], $hline8 ,$this->cols[2], $hline13);



//----------------------content filling---------------------------------

$this->row = $hline2;
$this->NewLine(5);
$this->fontSize += 4;
$this->Font('bold');
$this->TextWrap($left, $this->row,$this->cols[6], $this->title,'center');
$this->fontSize -= 4;
$this->Font();

//customer address
$this->NewLine(2);
$this->TextWrap($this->cols[0], $this->row,$this->cols[1], _("To,"),'center');
$this->NewLine();
$this->TextWrap($this->cols[1], $this->row,$this->cols[6], $Addr1['name'],'left');
$this->NewLine();
$this->TextWrapLines($this->cols[1], $this->cols[6], $Addr1['address'],'left');

//header
$this->row = $hline2- 185;
$data_col = $this->cols[2]-140;
$this->TextWrap($this->cols[1], $this->row,$this->cols[1], _("Invoice No."),'left');
$this->TextWrap($data_col, $this->row,$this->cols[1],$this->formData['trans_no'],'left');
$this->NewLine(1.3);
$this->TextWrap($this->cols[1], $this->row,$this->cols[1], _("Invoice Date"),'left');
$this->TextWrap($data_col, $this->row,$this->cols[1], $this->formData['tran_date'],'left');
$this->NewLine(1.3);
$this->TextWrap($this->cols[1], $this->row,$this->cols[1], _("Tenure Period"),'left');
$this->TextWrap($data_col, $this->row,$this->cols[2],$this->formData['trip_period'],'left');//static
$this->NewLine(1.3);
$this->TextWrap($this->cols[1], $this->row,$this->cols[1], _("Nature of Service"),'left');
$this->TextWrap($data_col, $this->row,$this->cols[1], _("Guest Transportation"),'left');//static





// TABLE HEADERS
$this->row = $hline3 - $this->lineHeight -3;
$this->Font('bold');
$count = count($this->headers);
$this->cols[$count] = $right - 3;
for ($i = 0; $i < $count; $i++)
	$this->TextCol($i, $i + 1, $this->headers[$i], -2);
$this->Font();


//TOTAL
$this->Font('bold');

$this->row = $hline6+5 ;
$this->TextWrap($this->cols[0], $this->row,$this->cols[4], _("Round Off"),'left');

//$this->TextWrap($this->cols[5], $this->row,$this->cols[5], _("0.00"),'left');

$this->row = $hline7+5 ;
$this->TextWrap($this->cols[0], $this->row,$this->cols[4], _("Grand Total"),'left');




//TAX NO AND PAN NO
$tax_pan_data_col = $tax_n_pan_col+2;
$this->row = $hline10+5 ;
$this->TextWrap($this->cols[0], $this->row,$tax_n_pan_col, _("SERVICE TAX NO."),'left');
$this->TextWrap($tax_pan_data_col, $this->row,$this->cols[3], $this->company['tax_no'],'left');

$this->row = $hline12+5 ;
$this->TextWrap($this->cols[0], $this->row,$tax_n_pan_col, _("PAN NO."),'left');
$this->TextWrap($tax_pan_data_col, $this->row,$this->cols[3], $this->company['pan_no'],'left');
$this->Font();


//company logo
$this->row = $hline10;
$logo = company_path() . "/images/" . $this->company['coy_logo'];
if ($this->company['coy_logo'] != '' && file_exists($logo))
{
	$this->AddImage($logo, $this->cols[4], $this->row,65,35);
}else{
	$this->Font('bold');
	$this->TextWrap($tax_n_pan_col, $this->row, $right, $this->company['coy_name'],'center');
	$this->Font();
}
$this->NewLine(7);
$this->TextWrap($tax_n_pan_col, $this->row, $right,  _("Authorised Signatory"),'center');

//table content row
$temp = $hline4 - $this->lineHeight - 2;

//totals cell
$totals_row =  $hline5 - $this->lineHeight - 2;

//price in words cell
$words_row =  $hline8 - $this->lineHeight - 2;


//-----------------------------------------------------------------------------







?>

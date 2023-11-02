<!--
Protection
Informations
Author: Klemen Vodopivec
License: FPDF
Description
This script allows to protect the PDF, that is to say prevent people from copying its content, print it or modify it.

Important: some PDF readers like Firefox ignore the protection settings, which strongly reduces the usefulness of this script.

SetProtection([array permissions [, string user_pass [, string owner_pass]]])

permissions: the set of permissions. Empty by default (only viewing is allowed).
user_pass: user password. Empty by default.
owner_pass: owner password. If not specified, a random value is used.

The permission array contains values taken from the following list:
copy: copy text and images to the clipboard
print: print the document
modify: modify it (except for annotations and forms)
annot-forms: add annotations and forms
The protection against modification is for people who have the full Acrobat product.

If you don't set any password, the document will open as usual. If you set a user password, the PDF viewer will ask for it before displaying the document. The owner password, if different from the user one, can be used to get full access.

Note: protecting a document requires to encrypt it. If an encryption extension is available (OpenSSL or Mcrypt), it is used. Otherwise encryption is done in PHP, which can increase the processing time significantly (especially if the document contains images or fonts).
Source
-->
<?php
/****************************************************************************
* Software: FPDF_Protection                                                 *
* Version:  1.05                                                            *
* Date:     2018-03-19                                                      *
* Author:   Klemen VODOPIVEC                                                *
* License:  FPDF                                                            *
*                                                                           *
* Thanks:  Cpdf (http://www.ros.co.nz/pdf) was my working sample of how to  *
*          implement protection in pdf.                                     *
****************************************************************************/

require('fpdf/fpdf.php');
require('fpdf_protection.php');

$pdf = new FPDF_Protection();
// $pdf->SetProtection(array('print'));
$pdf->SetProtection(array(), "123", "");
$pdf->AddPage();
$pdf->SetFont('Arial');
$pdf->Write(10,'You can print me but not copy my text.');
ob_end_clean();
$pdf->Output();
?>

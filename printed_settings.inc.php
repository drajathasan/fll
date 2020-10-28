<?php
/**
 * SENAYAN application printable data configuration
 *
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

/**
 * Function to load and override print settings from database
 */
function loadPrintSettings($dbs, $type) {
  global $sysconf;
  $barcode_settings_q = $dbs->query("SELECT setting_value FROM setting WHERE setting_name='".$type."_print_settings'");
  if ($barcode_settings_q->num_rows) {
    $barcode_settings_d = $barcode_settings_q->fetch_row();
    if ($barcode_settings_d[0]) {
      $barcode_settings = @unserialize($barcode_settings_d[0]);
      foreach ($barcode_settings as $setting_name => $val) {
        $sysconf['print'][$type][$setting_name] = $val;
      }
      return $sysconf['print'][$type];
    }
  }
}

// freeloan letter print settings
// by Drajat Hasan
// Logo Setting
$sysconf['print']['freeloan']['logo_surat'] = "kop-surat.png";
$sysconf['print']['freeloan']['items_per_row'] = 1;
// Content
$sysconf['print']['freeloan']['caption_letter'] = 'Surat Keterangan';
$sysconf['print']['freeloan']['declare_letter'] = '';
$sysconf['print']['freeloan']['result_letter'] = '';
$sysconf['print']['freeloan']['number_format'] = '/Perp/'; // /Perp/NamaInisialPerpustakaan
$sysconf['print']['freeloan']['institute'] = '';
$sysconf['print']['freeloan']['period'] = '';
$sysconf['print']['freeloan']['year'] = date("Y");
// Head Library Signature
$sysconf['print']['freeloan']['city'] = "Purwokertp";
$sysconf['print']['freeloan']['division_of_signature'] = 'Kepala Perpustakaan';
$sysconf['print']['freeloan']['name_of_signature'] = 'Sugeng Ndalu';
$sysconf['print']['freeloan']['id_of_signature'] = 'NIK. 216333';
$sysconf['print']['freeloan']['autoprint'] = 0;

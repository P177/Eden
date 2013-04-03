<?php
$res = mysql_query("SELECT * FROM $db_ntb_porovnani WHERE id='$id'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar = mysql_fetch_array($res);

// Zapsani dat z polozky card_reader do pole
$card_reader = explode (",", $ar[card_reader]); 

$czech_format_cena = number_format($ar[cena], 0, ',', ' '); //Spravne formatovani ceny

$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[vyrobce]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$vyrobce = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[rok_vyroby]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$rok_vyroby = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[display]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$display = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[cpu]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$cpu = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[audio_out]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$audio_out = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[ram]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$ram = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[vga]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$vga = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[vga_ram]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$vga_ram = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[cd]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$cd = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[polohovaci_zarizeni]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$polohovaci_zarizeni = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[net]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$net = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[wifi]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$wifi = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[usb]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$usb = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[pccard]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$pccard = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[chipset]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$chipset = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[display_res]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$display_res = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[display_typ]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$display_typ = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[baterie_vydrz]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$baterie_vydrz = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[ram_inst]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$ram_inst = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[hdd]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$hdd = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[hdd_inst]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$hdd_inst = $ar2[nazev];
$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE id='$ar[cd_inst]'") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
$ar2 = mysql_fetch_array($res2);
$cd_inst = $ar2[nazev];
?>
<!DOCtype HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo _NTBKARTA." - ".$ar[nazev];?></title>
<LINK REL="stylesheet" type="text/css" HREF="eden.css">
<META http-equiv="Content-Type" content="text/html; charset="windows-1250">
</head>
<body TOPMARGIN="0" LEFTMARGIN="0" bgcolor="#EBEBEC">

		<table width="580" cellspacing="2" cellpadding="1" border="0">
			<tr>
				<td align="center" class="nadpis"><?php echo _NTBKARTA." - ".$ar[nazev];?></td>
			</tr>
		</table>
		<br>
		<table width="580" cellspacing="0" cellpadding="4">
			<tr>
				<td width="580" align="center" colspan="2"><?php if ($ar[foto] != ""){?><img src="<?php echo $url_ntb."/".$ar[foto];?>" alt="" border="0"><br><?php }?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBNAME;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[nazev];?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBZNACKA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $vyrobce;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBTYP;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[typ];?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBVYROBCE;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[vyrobce2];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBTYP2;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[typ2];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBROK;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $rok_vyroby;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBDISP;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $display;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBDISPRES;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $display_res;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBDISPTYP;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $display_typ;?></td>
			</tr>			
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCPU;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $cpu;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCPUFREQ;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[cpu_freq];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCPUINST;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[cpu_inst];?></td>
			</tr>		
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBRAM;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ram;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBRAMINST;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ram_inst;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBVGA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $vga;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBVGARAM;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $vga_ram;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBHDD;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $hdd;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBHDDINST;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $hdd_inst;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCD;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $cd;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCDINST;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $cd_inst;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPOLOH;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $polohovaci_zarizeni;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBNET;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $net;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBWIFI;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $wifi;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBUSB;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $usb;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPCCARD;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $pccard;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBBIOS;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[bios];?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBACADAPTER;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[ac_adapter];?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBBATERIE;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[baterie];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBBATERIEVYDRZ;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[baterie_vydrz];?></td>
			</tr>		
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCHIPSET;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $chipset;?></td>
			</tr>	
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBVAHA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[vaha];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBVYSKA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[vyska];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBSIRKA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[sirka];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBDELKA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[delka];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPM;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[power_managment];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBAUDIO;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[audio];?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBAUDIOOUT;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $audio_out;?></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBAUDIOIN;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[audio_in] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBREPRO;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[repro] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCDPREHRAVANI;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[cd_prehravani] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBSCROLLBUTT;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[scrolling_button] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBIRDA;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[irda] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBBLUE;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[bluetooth] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBMODEM;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[modem] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBFDD;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[fdd] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBMIC;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[mic] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBMICIN;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[mic_in] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBFIREWIRE;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[firewire] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBSERIAL;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[serial] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPARALLEL;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[parallel] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPS2;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[ps2] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBKENSINGTON;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[kensington] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBVGAOUT;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[vga_out] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBTVOUT;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[tv_out] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBTVIN;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[tv_in] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPORTREPLIKATOR;?></strong></td>
				<td width="350" align="left" valign="top"><img src="<?php if ($ar[port_replikator] == "1"){echo "images/sys_yes.gif";} else {echo "images/sys_no.gif";}?>" width="18" height="18" alt="" border="0"></td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCARDREADER;?></strong></td>
				<td width="350" align="left" valign="top"></td>
			</tr>
<?php 					$cr = explode (",", $ar[card_reader]);
					$cr_pocet = count($cr);
					$res2 = mysql_query("SELECT * FROM $db_ntb_komponenty WHERE zkratka='CR' ORDER BY nazev ASC") or die ("<strong>File:</strong> ".__FILE__."<br /><strong>Line:</strong>".__LINE__."<br />".mysql_error());
					$i=0;
					while ($ar2 = mysql_fetch_array($res2)){
							echo '<tr>
									<td width="200" align="right" valign="top"><strong>'.$ar2[nazev].'</strong></td>
									<td width="350" align="left" valign="top"><img src="';
							$y=0;
							while($y <= $cr_pocet){
								if ($cr[$y] == $ar2[nazev]){ $yes[$y]= 1;}
								$y++;
							}
							$pocet_yes = count($yes);
							if ($pocet_yes > 0){echo 'images/sys_yes.gif';} else {echo "images/sys_no.gif";}
							echo '" width="18" height="18" alt="" border="0"></td></tr>';
						unset($yes);
						$i++;
					}
					?>

			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBCENA;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $czech_format_cena;?>,-</td>
			</tr>
			<tr>
				<td width="200" align="right" valign="top"><strong><?php echo _NTBPOPIS;?></strong></td>
				<td width="350" align="left" valign="top"><?php echo $ar[popis];?></td>
			</tr>
		</table>
</body>
</html>

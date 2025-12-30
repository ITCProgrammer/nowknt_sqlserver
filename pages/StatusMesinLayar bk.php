<?php
function NoMesin($mc)
{
include"koneksi.php";	
$sqlDB2 =" 
SELECT STSMC.STEPNUMBER,STSMC.LONGDESCRIPTION, ADSTORAGE.VALUESTRING,AD1.VALUEDATE,AD2.VALUEDATE AS RMPREQDATE ,AD3.VALUESTRING AS STATUSDEMAND , ITXVIEWKNTORDER.*,CURRENT_TIMESTAMP AS TGLS FROM ITXVIEWKNTORDER 
LEFT OUTER JOIN DB2ADMIN.PRODUCTIONDEMAND ON PRODUCTIONDEMAND.CODE = ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ON ADSTORAGE.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND ADSTORAGE.NAMENAME ='MachineNo'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD1 ON AD1.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD1.NAMENAME ='TglRencana'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD2 ON AD2.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD2.NAMENAME ='RMPReqDate' 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE AD3 ON AD3.UNIQUEID = PRODUCTIONDEMAND.ABSUNIQUEID AND AD3.NAMENAME ='StatusDemand'
LEFT OUTER JOIN (
SELECT STEPNUMBER,PRODUCTIONDEMANDCODE,PLANNEDOPERATIONCODE,PROGRESSSTATUS,LONGDESCRIPTION  FROM PRODUCTIONDEMANDSTEP  
WHERE PROGRESSSTATUS ='2' AND NOT (PLANNEDOPERATIONCODE='KNT1' OR PLANNEDOPERATIONCODE='INS1')
ORDER BY STEPNUMBER ASC
) STSMC ON STSMC.PRODUCTIONDEMANDCODE=ITXVIEWKNTORDER.PRODUCTIONDEMANDCODE
WHERE ITXVIEWKNTORDER.ITEMTYPEAFICODE ='KGF' AND (SCHEDULEDRESOURCECODE ='$mc' OR ADSTORAGE.VALUESTRING='$mc') AND ITXVIEWKNTORDER.PROGRESSSTATUS='2' 
ORDER BY STSMC.STEPNUMBER ASC
 ";
$stmt   = db2_exec($conn1,$sqlDB2, array('cursor'=>DB2_SCROLLABLE));
$rowdb2 = db2_fetch_assoc($stmt);

$sqlDB25 =" SELECT COUNT(WEIGHTREALNET ) AS JML,INSPECTIONENDDATETIME FROM 
ELEMENTSINSPECTION WHERE DEMANDCODE ='$rowdb2[PRODUCTIONDEMANDCODE]' AND ELEMENTITEMTYPECODE='KGF' AND QUALITYREASONCODE='PM'
GROUP BY INSPECTIONENDDATETIME ";	
$stmt5   = db2_exec($conn1,$sqlDB25, array('cursor'=>DB2_SCROLLABLE));
$rowdb25 = db2_fetch_assoc($stmt5);	

	
if($rowdb2['PROGRESSSTATUS']=="2" and $rowdb25['JML']>"0" ){
			$warnaD01="btn-danger";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="HOLD" ){
			$warnaD01="bg-navy";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="HABIS BENANG" ){
			$warnaD01="bg-black";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="TUNGGU BENANG GUDANG" ){
			$warnaD01="bg-maroon";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="ANTRI MESIN" ){
			$warnaD01="bg-yellow";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="TUNGGU PASANG BENANG" ){
			$warnaD01="bg-maroon";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="Tunggu Setting" ){
			$warnaD01="bg-blue";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb2['LONGDESCRIPTION']=="TUNGGU TES QUALITY" ){
			$warnaD01="bg-maroon";
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="0 ,0" or $rowdb24['IDS']=="0 ,0 ,0") ){
			$warnaD01="btn-warning";	
		}elseif($rowdb2['PROGRESSSTATUS']=="2" and $rowdb24['IDS']=="3 ,3" ){
			$warnaD01="btn-danger";	
		}else if($rowdb2['PROGRESSSTATUS']=="2" and ($rowdb24['IDS']=="2 ,0" or $rowdb24['IDS']=="0 ,2" or $rowdb24['IDS']=="2 ,2" or $rowdb24['IDS']=="2 ,3" ) ) {
			$warnaD01="btn-success";
		}else{
			$warnaD01="btn-default";
		}	
	return $warnaD01;
}
function Rajut($mc)
{
	
}
?>
<!-- Main content -->
      <div class="container-fluid">
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 1 dan 2</h3>
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table width="100%" border="0">
							<tbody>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P003"); ?>" id="M03" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M03"); ?>">M03</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P002"); ?>" id="M02" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M02"); ?>">M02</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P001"); ?>" id="M01" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M01"); ?>">M01</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P008"); ?>" id="R8" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R8"); ?>">R8&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P001"); ?>" id="R1" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R1"); ?>">R1&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U013"); ?>" id="E13" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E13"); ?>">E13</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U016"); ?>" id="E16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E16"); ?>">E16</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P027"); ?>" id="M27" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M27"); ?>">M27</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P036"); ?>" id="M36" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M36"); ?>">M36</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U003"); ?>" id="E03" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E03"); ?>">E03</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U038"); ?>" id="E38" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E38"); ?>">E38</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U044"); ?>" id="E44" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E44"); ?>">E44</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P052"); ?>" id="M52" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M52"); ?>">M52</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P047"); ?>" id="M47" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M47"); ?>">M47</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P042"); ?>" id="M42" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M42"); ?>">M42</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P037"); ?>" id="M37" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M37"); ?>">M37</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P057"); ?>" id="M57" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M57"); ?>">M57</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P062"); ?>" id="M62" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M62"); ?>">M62</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P077"); ?>" id="M77" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M77"); ?>">M77</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P069"); ?>" id="M69" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M69"); ?>">M69</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P070"); ?>" id="M70" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M70"); ?>">M70</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J021"); ?>" id="D21" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D21"); ?>">D21</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J016"); ?>" id="D16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D16"); ?>">D16</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J011"); ?>" id="D11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D11"); ?>">D11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J006"); ?>" id="D06" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D06"); ?>">D06</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO11J001"); ?>" id="D01" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D01"); ?>">D01</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P005"); ?>" id="M05" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M05"); ?>">M05</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P009"); ?>" id="M09" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M09"); ?>">M09</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P008"); ?>" id="M08" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M08"); ?>">M08</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P009"); ?>" id="R9" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R9"); ?>">R9&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P002"); ?>" id="R2" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R2"); ?>">R2&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U015"); ?>" id="E15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E15"); ?>">E15</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U018"); ?>" id="E18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E18"); ?>">E18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P028"); ?>" id="M28" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M28"); ?>">M28</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P035"); ?>" id="M35" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M35"); ?>">M35</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U005"); ?>" id="E05" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E05"); ?>">E05</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U039"); ?>" id="E39" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E39"); ?>">E39</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U045"); ?>" id="E45" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E45"); ?>">E45</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P053"); ?>" id="M53" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M53"); ?>">M53</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P048"); ?>" id="M48" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M48"); ?>">M48</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P043"); ?>" id="M43" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M43"); ?>">M43</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P038"); ?>" id="M38" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M38"); ?>">M38</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P058"); ?>" id="M58" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M58"); ?>">M58</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P063"); ?>" id="M63" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M63"); ?>">M63</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P078"); ?>" id="M78" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M78"); ?>">M78</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P068"); ?>" id="M68" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M68"); ?>">M68</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P071"); ?>" id="M71" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M71"); ?>">M71</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D22"); ?>" id="D22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D22"); ?>">D22</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D17"); ?>" id="D17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D17"); ?>">D17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D12"); ?>" id="D12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D12"); ?>">D12</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D07"); ?>" id="D07" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D07"); ?>">D07</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D02"); ?>" id="D02" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D02"); ?>">D02</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P006"); ?>" id="M06" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M06"); ?>">M06</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P011"); ?>" id="M11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M11"); ?>">M11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P010"); ?>" id="M10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M10"); ?>">M10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P010"); ?>" id="R10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R10"); ?>">R10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P003"); ?>" id="R3" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R3"); ?>">R3&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U014"); ?>" id="E14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E14"); ?>">E14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U017"); ?>" id="E17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E17"); ?>">E17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P029"); ?>" id="M29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M29"); ?>">M29</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P034"); ?>" id="M34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M34"); ?>">M34</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U021"); ?>" id="E21" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E21"); ?>">E21</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U040"); ?>" id="E40" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E40"); ?>">E40</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U046"); ?>" id="E46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E46"); ?>">E46</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P054"); ?>" id="M54" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M54"); ?>">M54</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P049"); ?>" id="M49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M49"); ?>">M49</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P044"); ?>" id="M44" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M44"); ?>">M44</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P039"); ?>" id="M39" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M39"); ?>">M39</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P059"); ?>" id="M59" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M59"); ?>">M59</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P064"); ?>" id="M64" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M64"); ?>">M64</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P079"); ?>" id="M79" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M79"); ?>">M79</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P072"); ?>" id="M72" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M72"); ?>">M72</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P073"); ?>" id="M73" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M73"); ?>">M73</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U011"); ?>" id="E11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E11"); ?>">E11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D18"); ?>" id="D18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D18"); ?>">D18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D13"); ?>" id="D13" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D13"); ?>">D13</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D08"); ?>" id="D08" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D08"); ?>">D08</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D03"); ?>" id="D03" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D03"); ?>">D03</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P007"); ?>" id="M07" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M07"); ?>">M07</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P014"); ?>" id="M14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M14"); ?>">M14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P012"); ?>" id="M12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M12"); ?>">M12</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P011"); ?>" id="R11" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R11"); ?>">R11</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P005"); ?>" id="R5" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R5"); ?>">R5&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U022"); ?>" id="E22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E22"); ?>">E22</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U019"); ?>" id="E19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E22"); ?>">E19</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P030"); ?>" id="M30" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M30"); ?>">M30</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P033"); ?>" id="M33" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M33"); ?>">M33</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U024"); ?>" id="E24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E24"); ?>">E24</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U041"); ?>" id="E41" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E41"); ?>">E41</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U047"); ?>" id="E47" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E47"); ?>">E47</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U050"); ?>" id="E50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E50"); ?>">E50</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P050"); ?>" id="M50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M50"); ?>">M50</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P045"); ?>" id="M45" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M45"); ?>">M45</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P040"); ?>" id="M40" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M40"); ?>">M40</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P060"); ?>" id="M60" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M60"); ?>">M60</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P065"); ?>" id="M65" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M65"); ?>">M65</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P080"); ?>" id="M80" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M80"); ?>">M80</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P074"); ?>" id="M74" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M74"); ?>">M74</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P075"); ?>" id="M75" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M75"); ?>">M75</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U010"); ?>" id="E10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E10"); ?>">E10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D19"); ?>" id="D19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D19"); ?>">D19</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D14"); ?>" id="D14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D14"); ?>">D14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D09"); ?>" id="D09" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D09"); ?>">D09</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D04"); ?>" id="D04" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D04"); ?>">D04</span></a></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P017"); ?>" id="M17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M17"); ?>">M17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P020"); ?>" id="M20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M20"); ?>">M20</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P023"); ?>" id="M23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M23"); ?>">M23</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P012"); ?>" id="R12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R12"); ?>">R12</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P006"); ?>" id="R6" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R6"); ?>">R6&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U023"); ?>" id="E23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E23"); ?>">E23</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U020"); ?>" id="E20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E20"); ?>">E20</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P031"); ?>" id="M31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M31"); ?>">M31</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P032"); ?>" id="M32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M32"); ?>">M32</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U025"); ?>" id="E25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E25"); ?>">E25</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U042"); ?>" id="E42" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E42"); ?>">E42</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U048"); ?>" id="E48" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E48"); ?>">E48</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U051"); ?>" id="E51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E51"); ?>">E51</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P051"); ?>" id="M51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M51"); ?>">M51</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P046"); ?>" id="M46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M46"); ?>">M46</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P041"); ?>" id="M41" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M41"); ?>">M41</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P061"); ?>" id="M61" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M61"); ?>">M61</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P066"); ?>" id="M66" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M66"); ?>">M66</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P081"); ?>" id="M81" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M81"); ?>">M81</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P076"); ?>" id="M76" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M76"); ?>">M76</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U004"); ?>" id="E04" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E04"); ?>">E04</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U009"); ?>" id="D20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E09"); ?>">E09</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D20"); ?>" id="D20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D20"); ?>">D20</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D15"); ?>" id="D15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D15"); ?>">D15</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D10"); ?>" id="D10" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D10"); ?>">D10</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("D05"); ?>" id="D05" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D05"); ?>">D05</span></a></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P007"); ?>" id="R7" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R7"); ?>">R7&nbsp;&nbsp;</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U001"); ?>" id="E01" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E01"); ?>">E01</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U026"); ?>" id="E26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E26"); ?>">E26</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P026"); ?>" id="M26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M26"); ?>">M26</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P067"); ?>" id="M67" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M67"); ?>">M67</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U027"); ?>" id="E27" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E27"); ?>">E27</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U043"); ?>" id="E43" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E43"); ?>">E43</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U049"); ?>" id="E49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E49"); ?>">E49</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO11P082"); ?>" id="M82" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M82"); ?>">M82</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TT11U002"); ?>" id="E02" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E02"); ?>">E02</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P018"); ?>" id="M18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M18"); ?>">M18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P021"); ?>" id="M21" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M21"); ?>">M21</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P024"); ?>" id="M24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M24"); ?>">M24</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="4">Tg. Benang Warehouse <span class="label label-warning">
											<?php echo $totTBW;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Tg. Setting <span class="label label-primary">
											<?php echo $totTS;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Sedang Jalan <span class="label label-success">
											<?php echo $totSJ;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tes Quality <span class="label bg-abu">
											<?php echo $totTQ;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P019"); ?>" id="M19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M19"); ?>">M19</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P022"); ?>" id="M22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M22"); ?>">M22</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("ST11P025"); ?>" id="M25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M25"); ?>">M25</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Antri Mesin <span class="label bg-kuning">
											<?php echo $totAM;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tidak Ada PO <span class="label label-default">
											<?php echo $totTAP;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Perbaikan Mesin <span class="label label-danger">
											<?php echo $totPM;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tg. Benang Supp <span class="label bg-fuchsia">
											<?php echo $totTBS;?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P018"); ?>" id="R18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R18"); ?>">R18</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P015"); ?>" id="R15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R15"); ?>">R15</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U059"); ?>" id="E59" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E59"); ?>">E59</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U055"); ?>" id="E55" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E55"); ?>">E55</span></a></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Potensi Delay <span class="label btn-sm border-dashed bg-abu">
											<?php echo $totPTD;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Urgent <span class="label btn-sm blink_me bg-abu">
											<?php echo $totURG;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Rajut Sample <span class="label btn-sm bg-abu bulat">
											<?php echo $totRS;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Tg. Pasang Benang <span class="label bg-purple"><?php echo $totTPB;?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P017"); ?>" id="R17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R17"); ?>">R17</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P013"); ?>" id="R13" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R13"); ?>">R13</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U058"); ?>" id="E58" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E58"); ?>">E58</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U054"); ?>" id="E54" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E54"); ?>">E54</span></a></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Hold <span class="label bg-teal"><?php echo $totHLD;?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Siap Jalan <span class="label bg-black">
											<?php echo $totKOp;?></span></td>
									<td>&nbsp;</td>
									<td colspan="3">Total Mesin <span class="label label-danger">
											<?php echo $totMesin; ?></span></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">Kurang Cones <span class="label bg-navy">
											<?php echo $totKCo;?></span></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P016"); ?>" id="R16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R16"); ?>">R16</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U061"); ?>" id="E61" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E61"); ?>">E61</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U057"); ?>" id="E57" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E57"); ?>">E57</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U053"); ?>" id="E53" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E53"); ?>">E53</span></a></td>
								</tr>
								<tr>
									<td colspan="17">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="3">&nbsp;</td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("RI11P014"); ?>" id="R14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("R14"); ?>">R14</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U060"); ?>" id="E60" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E60"); ?>">E60</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U056"); ?>" id="E56" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E56"); ?>">E56</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF11U052"); ?>" id="E52" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E52"); ?>">E52</span></a></td>
								</tr>
								<tr>
									<td colspan="26" style="padding: 5px;">
										<marquee class="teks-berjalan" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
											<?php echo $rNews['news_line'];?>
										</marquee>
									</td>
								</tr>
								<tr>
									<td colspan="26" style="padding: 5px;">&nbsp;</td>
								</tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J063"); ?>" id="D63" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D63"); ?>">D63</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J064"); ?>" id="D64" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D64"); ?>">D64</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J065"); ?>" id="D65" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D65"); ?>">D65</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J066"); ?>" id="D66" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D66"); ?>">D66</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J067"); ?>" id="D67" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D67"); ?>">D67</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J053"); ?>" id="D53" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D53"); ?>">D53</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J054"); ?>" id="D54" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D54"); ?>">D54</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J055"); ?>" id="D55" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D55"); ?>">D55</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J056"); ?>" id="D56" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D56"); ?>">D56</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J057"); ?>" id="D57" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D57"); ?>">D57</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J058"); ?>" id="D58" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D58"); ?>">D58</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J059"); ?>" id="D59" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D59"); ?>">D59</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J060"); ?>" id="D60" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D60"); ?>">D60</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J061"); ?>" id="D61" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D61"); ?>">D61</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J062"); ?>" id="D62" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D62"); ?>">D62</span></a></td>
								  <td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J043"); ?>" id="D43" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D43"); ?>">D43</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J044"); ?>" id="D44" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D44"); ?>">D44</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J045"); ?>" id="D45" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D45"); ?>">D45</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J046"); ?>" id="D46" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D46"); ?>">D46</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J047"); ?>" id="D47" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D47"); ?>">D47</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J048"); ?>" id="D48" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D48"); ?>">D48</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J049"); ?>" id="D49" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D49"); ?>">D49</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J050"); ?>" id="D50" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D50"); ?>">D50</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J051"); ?>" id="D51" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D51"); ?>">D51</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J052"); ?>" id="D52" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D52"); ?>">D52</span></a></td>
								  <td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr style="height: 0.1in;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J033"); ?>" id="D33" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D33"); ?>">D33</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J034"); ?>" id="D34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D34"); ?>">D34</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J035"); ?>" id="D35" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D35"); ?>">D35</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J036"); ?>" id="D36" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D36"); ?>">D36</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J037"); ?>" id="D37" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D37"); ?>">D37</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J038"); ?>" id="D38" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D38"); ?>">D38</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J039"); ?>" id="D39" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D39"); ?>">D39</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J040"); ?>" id="D40" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D40"); ?>">D40</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J041"); ?>" id="D41" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D41"); ?>">D41</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J042"); ?>" id="D42" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D42"); ?>">D42</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J023"); ?>" id="D23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D23"); ?>">D23</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J024"); ?>" id="D24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D24"); ?>">D24</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J025"); ?>" id="D25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D25"); ?>">D25</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J026"); ?>" id="D26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D26"); ?>">D26</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J027"); ?>" id="D27" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D27"); ?>">D27</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J028"); ?>" id="D28" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D28"); ?>">D28</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J029"); ?>" id="D29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D29"); ?>">D29</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J030"); ?>" id="D30" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D30"); ?>">D30</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J031"); ?>" id="D31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D31"); ?>">D31</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO22J032"); ?>" id="D32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("D32"); ?>">D32</span></a></td>
								  <td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr style="height: 0.1in;">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G033"); ?>" id="E33" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E33"); ?>">E33</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G034"); ?>" id="E34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E34"); ?>">E34</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G035"); ?>" id="E35" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E35"); ?>">E35</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G036"); ?>" id="E36" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E36"); ?>">E36</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G037"); ?>" id="E37" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E37"); ?>">E37</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G028"); ?>" id="E28" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E28"); ?>">E28</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G029"); ?>" id="E29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E29"); ?>">E29</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G030"); ?>" id="E30" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E30"); ?>">E30</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G031"); ?>" id="E31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E31"); ?>">E31</span></a></td>
									<td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF22G032"); ?>" id="E32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E32"); ?>">E32</span></a></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
						</table>
              </div>
              <!-- /.card-body -->
            </div>	
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 3</h3>
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
              <table width="100%" border="0">								
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M036"); ?>" id="H36" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H36"); ?>">H36</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M037"); ?>" id="H37" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H37"); ?>">H37</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M038"); ?>" id="H38" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H38"); ?>">H38</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M039"); ?>" id="H39" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H39"); ?>">H39</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td width="26%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="9%">&nbsp;</td>
								  <td width="3%">&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H0101"); ?>" id="E101" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E101"); ?>">E101</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H0100"); ?>" id="E100" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E100"); ?>">E100</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H099"); ?>" id="E99" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E99"); ?>">E99</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H098"); ?>" id="E98" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E98"); ?>">E98</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H097"); ?>" id="E97" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E97"); ?>">E97</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H096"); ?>" id="E96" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E96"); ?>">E96</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M028"); ?>" id="H28" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H28"); ?>">H28</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M029"); ?>" id="H29" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H29"); ?>">H29</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M030"); ?>" id="H30" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H30"); ?>">H30</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M031"); ?>" id="H31" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H31"); ?>">H31</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M032"); ?>" id="H32" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H32"); ?>">H32</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M033"); ?>" id="H33" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H33"); ?>">H33</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M034"); ?>" id="H34" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H34"); ?>">H34</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M035"); ?>" id="H35" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H35"); ?>">H35</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H095"); ?>" id="E95" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E95"); ?>">E95</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H094"); ?>" id="E94" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E94"); ?>">E94</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H093"); ?>" id="E93" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E93"); ?>">E93</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H092"); ?>" id="E92" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E92"); ?>">E92</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H091"); ?>" id="E91" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E91"); ?>">E91</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H090"); ?>" id="E90" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E90"); ?>">E90</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H089"); ?>" id="E89" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E89"); ?>">E89</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M020"); ?>" id="H20" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H20"); ?>">H20</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M021"); ?>" id="H21" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H21"); ?>">H21</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M022"); ?>" id="H22" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H22"); ?>">H22</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M023"); ?>" id="H23" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H23"); ?>">H23</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M024"); ?>" id="H24" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H24"); ?>">H24</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M025"); ?>" id="H25" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H25"); ?>">H25</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M026"); ?>" id="H26" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H26"); ?>">H26</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M027"); ?>" id="H27" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H27"); ?>">H27</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H088"); ?>" id="E88" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E88"); ?>">E88</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H087"); ?>" id="E87" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E87"); ?>">E87</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H086"); ?>" id="E86" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E86"); ?>">E86</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H085"); ?>" id="E85" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E85"); ?>">E85</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H084"); ?>" id="E84" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E84"); ?>">E84</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H083"); ?>" id="E83" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E83"); ?>">E83</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H082"); ?>" id="E82" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E82"); ?>">E82</span></a></td>
							  </tr>
								<tr style="height: 0.1in;">
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M012"); ?>" id="H12" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H12"); ?>">H12</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M013"); ?>" id="H13" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H13"); ?>">H13</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M014"); ?>" id="H14" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H14"); ?>">H14</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M015"); ?>" id="H15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H15"); ?>">H15</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M016"); ?>" id="H16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H16"); ?>">H16</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M017"); ?>" id="H17" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H17"); ?>">H17</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M018"); ?>" id="H18" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H18"); ?>">H18</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M019"); ?>" id="H19" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H19"); ?>">H19</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H081"); ?>" id="E81" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E81"); ?>">E81</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H080"); ?>" id="E80" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E80"); ?>">E80</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H079"); ?>" id="E79" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E79"); ?>">E79</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H078"); ?>" id="E78" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E78"); ?>">E78</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H077"); ?>" id="E77" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E77"); ?>">E77</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H076"); ?>" id="E76" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E76"); ?>">E76</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H075"); ?>" id="E75" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E75"); ?>">E75</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M004"); ?>" id="H04" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H04"); ?>">H04</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M005"); ?>" id="H05" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H05"); ?>">H05</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M006"); ?>" id="H06" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H06"); ?>">H06</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M007"); ?>" id="H07" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H07"); ?>">H07</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M008"); ?>" id="H08" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H08"); ?>">H08</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M009"); ?>" id="H09" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H09"); ?>">H09</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H074"); ?>" id="E74" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E74"); ?>">E74</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H073"); ?>" id="E73" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E73"); ?>">E73</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H072"); ?>" id="E72" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E72"); ?>">E72</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H071"); ?>" id="E71" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E71"); ?>">E71</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H070"); ?>" id="E70" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E70"); ?>">E70</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H069"); ?>" id="E69" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E69"); ?>">E69</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H068"); ?>" id="E68" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E68"); ?>">E68</span></a></td>
							  </tr>
								<tr style="height: 0.1in;">
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P015"); ?>" id="M15" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M15"); ?>">M15</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P016"); ?>" id="M16" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M16"); ?>">M16</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P055"); ?>" id="M55" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M55"); ?>">M55</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23P056"); ?>" id="M56" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("M56"); ?>">M56</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M001"); ?>" id="H01" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H01"); ?>">H01</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M002"); ?>" id="H02" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H02"); ?>">H02</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("SO23M003"); ?>" id="H03" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("H03"); ?>">H03</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H067"); ?>" id="E67" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E67"); ?>">E67</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H066"); ?>" id="E66" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E66"); ?>">E66</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H065"); ?>" id="E65" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E65"); ?>">E65</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H064"); ?>" id="E64" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E64"); ?>">E64</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H063"); ?>" id="E63" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E63"); ?>">E63</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF23H062"); ?>" id="E62" data-toggle="tooltip" data-html="true" title="<?php echo Rajut("E62"); ?>">E62</span></a></td>
							  </tr>
								<tr>
									<td colspan="26" style="padding: 5px;">&nbsp;</td>
								</tr>
								<tr>
								  <td colspan="26" style="padding: 5px;">&nbsp;</td>
							  </tr>
							</tbody>
						</table>  
              </div>
              <!-- /.card-body -->
            </div>  
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Status Mesin Rajut Knitting ITTI Lantai 4</h3>
			</div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table width="100%" border="0">	 							
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S100"); ?>" id="D100" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D100"); ?>">D100</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S101"); ?>" id="D101" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D101"); ?>">D101</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S104"); ?>" id="D102" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D102"); ?>">D102</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S104"); ?>" id="D103" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D103"); ?>">D103</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S104"); ?>" id="D104" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D104"); ?>">D104</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td width="0%">&nbsp;</td>
								  <td width="0%">&nbsp;</td>
								  <td width="32%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td width="2%">&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S092"); ?>" id="D92" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D92"); ?>">D92</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S093"); ?>" id="D93" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D93"); ?>">D93</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S094"); ?>" id="D94" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D94"); ?>">D94</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S095"); ?>" id="D95" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D95"); ?>">D95</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S096"); ?>" id="D96" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D96"); ?>">D96</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S097"); ?>" id="D97" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D97"); ?>">D97</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S098"); ?>" id="D98" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D98"); ?>">D98</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S099"); ?>" id="D99" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D99"); ?>">D99</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
			  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S084"); ?>" id="D84" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D84"); ?>">D84</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S085"); ?>" id="D85" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D85"); ?>">D85</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S086"); ?>" id="D86" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D86"); ?>">D86</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S087"); ?>" id="D87" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D87"); ?>">D87</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S088"); ?>" id="D88" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D88"); ?>">D88</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S089"); ?>" id="D89" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D89"); ?>">D89</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S090"); ?>" id="D90" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D90"); ?>">D90</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S091"); ?>" id="D91" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D91"); ?>">D91</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
			  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S076"); ?>" id="D76" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D76"); ?>">D76</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S077"); ?>" id="D77" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D77"); ?>">D77</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S078"); ?>" id="D78" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D78"); ?>">D78</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S079"); ?>" id="D79" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D79"); ?>">D79</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S080"); ?>" id="D80" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D80"); ?>">D80</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S081"); ?>" id="D81" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D81"); ?>">D81</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S082"); ?>" id="D82" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D82"); ?>">D82</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S083"); ?>" id="D83" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D83"); ?>">D83</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H111"); ?>" id="E111" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E111"); ?>">E111</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H110"); ?>" id="E110" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E110"); ?>">E110</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H109"); ?>" id="E109" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E109"); ?>">E109</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H108"); ?>" id="E108" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E108"); ?>">E108</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H107"); ?>" id="E107" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E107"); ?>">E107</span></a></td>
							  </tr>
								<tr>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S068"); ?>" id="D68" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D68"); ?>">D68</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S069"); ?>" id="D69" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D69"); ?>">D69</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S070"); ?>" id="D70" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D70"); ?>">D70</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S071"); ?>" id="D71" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D71"); ?>">D71</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S072"); ?>" id="D72" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D72"); ?>">D72</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S073"); ?>" id="D73" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D73"); ?>">D73</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S074"); ?>" id="D74" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D74"); ?>">D74</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("DO23S075"); ?>" id="D75" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("D75"); ?>">D75</span></a></td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H106"); ?>" id="E106" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E106"); ?>">E106</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H105"); ?>" id="E105" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E105"); ?>">E105</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H104"); ?>" id="E104" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E104"); ?>">E104</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H103"); ?>" id="E103" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E103"); ?>">E103</span></a></td>
								  <td><a><span class="detail_status btn btn-sm <?php echo NoMesin("TF24H102"); ?>" id="E102" data-toggle="tooltip" data-html="true" title="<?php //echo Rajut("E102"); ?>">E102</span></a></td>
							  </tr>
								<tr style="height: 0.1in;">
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
							  </tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr style="height: 0.1in;">
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
								  <td></td>
							  </tr>
								<tr>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
								  <td>&nbsp;</td>
							  </tr>
								<tr>
									<td colspan="26" style="padding: 5px;">&nbsp;</td>
								</tr>
								<tr>
								  <td colspan="26" style="padding: 5px;">&nbsp;</td>
							  </tr>
							</tbody>
						</table>
              </div>
              <!-- /.card-body -->
            </div>  
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<script>
	$(function () {
		//Datepicker
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker2').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	
});		
</script>
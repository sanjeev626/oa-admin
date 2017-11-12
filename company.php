
<script type="text/javascript">
 function callDelete(gid)
 {
	if(confirm('Are you sure to delete ?'))
	{
	window.location='?manager=company&did='+gid;
	}
 } 
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#usersearch").autocomplete({
            source: 'ajaxoverallsearch.php?type=company' + $("#usersearch").val(),
            minLength: 1,
            select: function (e, ui) {

                location.href = ui.item.the_link;

            }

        });
    });
</script>

<?php
if(isset($_GET['did']))
{
	$delId = $_GET['did'];
	$mess = $mydb->deleteQuery('tbl_company','id='.$delId);
}

if(isset($_GET['company_id']))
{
	$company_id = $_GET['company_id'];
}
else
{
	$company_id = 0;
}

if(isset($_GET['companyname']))
{
    $id=$_GET['companyname'];
    $result1 = $mydb->getQuery('*', 'tbl_company', 'id=' . $id);
    ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
            <tr class="TitleBar">
                <td colspan="3" class="TtlBarHeading">Company list
                    <div style="float:right"></div>
                </td>
                <td class="TtlBarHeading" colspan="2" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by Company" class="button" style="width:250px;"/></td>

                </td>
            </tr>
            <tr>

                <td class="titleBarB"><strong>Full Name</strong></td>
                <td width="20%" class="titleBarB"><strong>Address</strong></td>
                <td width="10%" class="titleBarB"><strong>Landline</strong></td>
                <td width="10%" class="titleBarB"><strong>Mobile</strong></td>
                <td width="8%" class="titleBarB">&nbsp;</td>
            </tr>
                <?php
                $counter = 0;
                while ($rasMember = $mydb->fetch_array($result1)) {

                    $gid = $rasMember['id'];
                    ?>

                    <tr>

                        <td class="titleBarA" valign="top"><a
                                href="index.php?manager=company&company_id=	<?php echo $rasMember['id'] ?>"><?php echo stripslashes(ucfirst($rasMember['fullname'])); ?></a>
                        </td>
                        <td class="titleBarA" valign="top"><?php echo ucfirst($rasMember['address']); ?></td>
                        <td class="titleBarA" valign="top"><?php echo $rasMember['landline']; ?></td>
                        <td class="titleBarA" valign="top"><?php echo $rasMember['mobile']; ?></td>
                        <td class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH; ?>company_manage&id=<?php echo $rasMember['id']; ?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a href="javascript:void(0);" onclick="callDelete('<?php echo $gid; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

<?php
}
else {
   // $parent_id = $mydb->getValue('parent_id', 'tbl_company', 'id=' . $company_id);
    $result = $mydb->getQuery('*', 'tbl_company', 'parent_id="'.$company_id.'"  ORDER BY fullname' );
    $countCompany = mysql_num_rows($result);
    $countMedicine = $mydb->getCount('id', 'tbl_medicine', 'company_id='.$company_id);
    if ($countCompany > 0) {
        ?>
        <!-- List Company -->
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
            <tr class="TitleBar">
                <td colspan="2"
                    class="TtlBarHeading"><?php if ($company_id == 0) echo 'Company List :'; else echo 'Sub Division of ';
                    echo $mydb->getValue('fullname', 'tbl_company', "id=" . $company_id); ?>
                    <div style="float:right"></div>
                </td>
                <td class="TtlBarHeading" colspan="2" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by Company" class="button" style="width:250px;"/></td>
                <td class="TtlBarHeading" style="width:50px;"><input name="btnAdd" type="button" value="Add" class="button"onClick="window.location='<?php echo ADMINURLPATH; ?>company_manage'"/>
                </td>
            </tr>
            <tr>

                <td class="titleBarB"><strong>Full Name</strong></td>
                <td width="20%" class="titleBarB"><strong>Address</strong></td>
                <td width="10%" class="titleBarB"><strong>Landline</strong></td>
                <td width="10%" class="titleBarB"><strong>Mobile</strong></td>
                <td width="8%" class="titleBarB">&nbsp;</td>
            </tr>
            <?php
            $counter = 0;
            while ($rasMember = $mydb->fetch_array($result)) {

                $gid = $rasMember['id'];
                ?>

                <tr>

                    <td class="titleBarA" valign="top"><a
                            href="index.php?manager=company&company_id=	<?php echo $rasMember['id'] ?>"><?php echo stripslashes(ucfirst($rasMember['fullname'])); ?></a>
                    </td>
                    <td class="titleBarA" valign="top"><?php echo ucfirst($rasMember['address']); ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['landline']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['mobile']; ?></td>
                    <td class="titleBarA" valign="top" align="center"><a
                            href="<?php echo ADMINURLPATH; ?>company_manage&id=<?php echo $rasMember['id']; ?>"><img
                                src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a
                            href="javascript:void(0);" onclick="callDelete('<?php echo $gid; ?>')"><img
                                src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    if ($countMedicine > 0) {
        $result5 = $mydb->getQuery('*', 'tbl_medicine', 'company_id="'.$company_id.'" ORDER BY medicine_name');
        ?>
        <!-- List Medicine -->
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
            <tr class="TitleBar">
                <td colspan="7" class="TtlBarHeading">Medicine list: <?php if ($parent_id > 0) { ?> <a
                        href="<?php echo ADMINURLPATH; ?>company&company_id=<?php echo $parent_id; ?>"><?php echo $mydb->getValue('fullname', 'tbl_company', 'id=' . $parent_id); ?></a><?php }
                    echo ' >> ' . $mydb->getValue('fullname', 'tbl_company', "id=" . $company_id); ?>
                </td>
            </tr>
            <tr>
                <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
                
                <td width="20%" class="titleBarB"><strong>MEDICINE NAME</strong></td>
                <td width="20%" class="titleBarB"><strong>COMPOSITION</strong></td>
                <td width="20%" class="titleBarB"><strong>INDICATIONS</strong></td>
                <td width="20%" class="titleBarB"><strong>SIDE EFFECTS</strong></td>
                <td width="10%" class="titleBarB">&nbsp;</td>


            </tr>

            <?php
            while ($rasMember5 = $mydb->fetch_array($result5)) {

                $gid = $rasMember5['id'];
                ?>
                <tr>
                    <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]"
                                                                             value="<?php echo $rasMember['id']; ?>"/><?php echo ++$counter; ?>
                    </td>
                    
                    <td class="titleBarA" valign="top"><?php echo $rasMember5['medicine_name']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember5['composition']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember5['indications']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember5['side_effects']; ?></td>
                    <td class="titleBarA" valign="top" align="center"><a
                            href="<?php echo ADMINURLPATH; ?>medicine_manage&id=<?php echo $rasMember5['id']; ?>"><img
                                src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a
                            href="javascript:void(0);" onclick="callDelete('<?php //echo $gid;
                        ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24"
                                   title="Delete"></a></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
}
?>
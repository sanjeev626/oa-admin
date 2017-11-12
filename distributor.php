<script type="text/javascript">
 function callDelete(gid)
 {
	if(confirm('Are you sure to delete ?'))
	{
		window.location='?manager=distributor&did='+gid;
	}
 } 
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#usersearch").autocomplete({
            source: 'ajaxoverallsearch.php?type=distributor' + $("#usersearch").val(),
            minLength: 1,
            select: function (e, ui) {


                location.href = ui.item.the_link;

            }

        });
    });
</script>

<?php
if(isset($_POST['btnUpdate']))
{
	$count = count($_POST['eid']);
	for($i=0;$i<$count;$i++)
	{
		$eid = $_POST['eid'][$i];
		$data='';
		$data['ordering'] = $_POST['ordering'][$i];				
		$mydb->updateQuery('tbl_distributor',$data,'id='.$eid);
	}
}

if(isset($_GET['did']))
{
	$delId = $_GET['did'];
	$mess = $mydb->deleteQuery('tbl_distributor','id='.$delId);
}


if(isset($_GET['distributorname']))
{
    $id=$_GET['distributorname'];
    $result1 = $mydb->getQuery('*', 'tbl_distributor', 'id=' . $id);
    ?>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
        <tr class="TitleBar">
            <td colspan="3" class="TtlBarHeading">Distributor list
                <div style="float:right"></div>
            </td>
            <td class="TtlBarHeading" colspan="2" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by distributor" class="button" style="width:250px;"/></td>

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
                <td class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH; ?>distributor_manage&id=<?php echo $rasMember['id']; ?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a href="javascript:void(0);" onclick="callDelete('<?php echo $gid; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>

    <?php
}
else {

$num_rec_per_page = 10;
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
};
$start_from = ($page - 1) * $num_rec_per_page;
$result = $mydb->getQuery('*', 'tbl_distributor', '1=1 ORDER BY fullname  limit ' . $start_from . ',' . $num_rec_per_page . '');

$count = mysql_num_rows($result);
?>

<form action="" method="post" name="tbl_distributor">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">

        <?php if (isset($_GET['message'])) { ?>
            <tr>
                <td colspan="2" class="message"><?php echo $_GET['message']; ?></td>
            </tr>
        <?php } ?>
        <tr class="TitleBar">
            <td colspan="5" class="TtlBarHeading">Distributor
                <div style="float:right"></div>
            </td>
            <td class="TtlBarHeading" colspan="2" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by distributor" class="button" style="width:250px;"/></td>
            <td class="TtlBarHeading" style="width:50px;"><input name="btnAdd" type="button" value="Add" class="button" onClick="window.location='<?php echo ADMINURLPATH; ?>distributor_manage'"/>
            </td>
        </tr>
        <?php
        if ($count != 0) {
            ?>
            <tr>
                <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
                <td class="titleBarB"><strong>Full Name</strong></td>
                <td width="20%" class="titleBarB"><strong>PAN No</strong></td>
                <td width="20%" class="titleBarB"><strong>DDA REGD</strong></td>
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
                    <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]"
                                                                             value="<?php echo $rasMember['id']; ?>"/><?php echo ++$counter; ?>
                    </td>
                    <td class="titleBarA" valign="top"><?php echo stripslashes(ucfirst($rasMember['fullname'])); ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['pan_number']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['dda_regd']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo ucfirst($rasMember['address']); ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['landline']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['mobile']; ?></td>
                    <td class="titleBarA" valign="top" align="center"><a
                            href="<?php echo ADMINURLPATH; ?>distributor_manage&id=<?php echo $rasMember['id']; ?>"><img
                                src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a> <a
                            href="javascript:void(0);" onclick="callDelete('<?php echo $gid; ?>')"><img
                                src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
                    </td>
                </tr>

                <?php
            }
            ?>
            <tr>
                <?php //<td class="titleBarA" align="center" valign="top"><input type="submit" name="btnUpdate" id="btnUpdate" value="Save" class="button" /></td>
                ?>
                <td class="titleBarA" valign="top">&nbsp;</td>
                <td class="titleBarA" valign="top">&nbsp;</td>
                <td class="titleBarA" valign="top">&nbsp;</td>
                <td class="titleBarA" valign="top">&nbsp;</td>
                <td class="titleBarA" valign="top">&nbsp;</td>
                <td class="titleBarA" valign="top">&nbsp;</td>
                <td class="titleBarA" valign="top" align="center">&nbsp;</td>
            </tr>
            <?php
        } else {
            ?>
            <tr>

                <td class="message" colspan="4">No distributors has been Added</td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    if (isset($page)) {
        ?>

        <nav style="text-align:center;">
            <ul class="pagination">
                <?php
                $rs_result = $mydb->getCount('id', 'tbl_distributor');

                $total_pages = ceil($rs_result / $num_rec_per_page);

                if ($page <= 1) {

                } else {
                    $j = $page - 1;
                    echo '<li >
                            <a href="' . ADMINURLPATH . 'distributor&page=' . $j . '" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>';
                }


                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i <> $page) {

                        echo '<li><a href ="' . ADMINURLPATH . 'distributor&page=' . $i . '" >' . $i . '</a></li>';
                    } else {
                        echo '<li >

                                <span aria-hidden="true">' . $i . '</span>
                            </a>
                        </li>';
                    }
                }


                if ($page == $total_pages) {

                } else {
                    $j = $page + 1;
                    echo "<li>
                    <a href='" . ADMINURLPATH . 'distributor&page=' . $j . "' aria-label='Next'>
                        <span aria-hidden='true'>&raquo;</span>
                    </a>
                </li>";
                }
                ?>
            </ul>
        </nav>

    <?php }
    }?>
</form>
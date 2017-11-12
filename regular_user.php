<!--=======Script for autocomplete and delete section===============-->
<script type="text/javascript">
 function callDelete(gid)
 {
	if(confirm('Are you sure to delete ?'))
	{
	window.location='?manager=regular_user&did='+gid;
	}
 }
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#usersearch").autocomplete({
            source: 'ajaxoverallsearch.php?type=user' + $("#usersearch").val(),
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
					
		$mydb->updateQuery('tbl_user',$data,'id='.$eid);
	}
}

if(isset($_GET['did']))
{
	$delId = $_GET['did'];
    $mess = $mydb->deleteQuery('users','id='.$delId);
}

if(isset($_GET['username']))
{
    $id=$_GET['username'];
    $result1 = $mydb->getQuery('*', 'users','id='.$id);
?>

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
        <tr class="TitleBar">
            <td colspan="2" class="TtlBarHeading">Regular Users
                <div style="float:right"></div>
            </td>
            <td class="TtlBarHeading" colspan="4" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by Name" class="button" style="width:250px;"/></td>

            </td>

        </tr>

        <tr>
            <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
            <td width="10%" class="titleBarB" align="center"><strong>Name</strong></td>
            <td width="20%" class="titleBarB" align="center"><strong>Email</strong></td>
            <td width="15%" class="titleBarB" align="center"><strong>Contact Number</strong></td>
            <td width="15%" class="titleBarB" align="center"><strong>Reference</strong></td>
            <td width="15%" class="titleBarB" align="center"><strong>Action</strong></td>
        </tr>
        <?php
        $counter = 0;
        while ($rasMember = $mydb->fetch_array($result1))
        {
            $gid = $rasMember['id'];
            ?>
            <tr id="userinfo<?php echo $rasMember['id']; ?>">
                <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]"
                                                                         value="<?php echo $rasMember['id']; ?>"/><?php echo ++$counter; ?>
                </td>
                <td class="titleBarA" valign="top"><?php echo stripslashes($rasMember['name']); ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['email']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['address']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['phone'];?></td>
                 <td class="titleBarA" valign="top"><a href="<?php echo ADMINURLPATH; ?>referencelist&name=<?php echo $rasMember['reference']; ?>"><?php echo $rasMember['reference']; ?></a></td>

                <!--<td class="titleBarA" valign="top"><img src="../upload/2015/06/<?php //echo $rasMember['filename'];
                ?>" height="100" /></td>-->
                <td class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH; ?>user_manage&id=<?php echo $rasMember['id']; ?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a>
                    <a href="javascript:void(0);" onclick="callDelete('<?php echo $gid; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
                    <a href="<?php echo ADMINURLPATH; ?>gallery&id=<?php echo $rasMember['id']; ?>"><img src="images/arrow.png" alt="imagedisplay" width="24" height="24" title="Prescription_display"></a></td>
            </tr>
            <?php
        }
        ?>

    </table>

<?php  }

else
{
    $num_rec_per_page = 15;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    } else {
        $page = 1;
    };
    $start_from = ($page - 1) * $num_rec_per_page;

    $result = $mydb->getQuery('*', 'users', 'user_type="regular_user"  ORDER BY name limit ' . $start_from . ',' . $num_rec_per_page . '');
    $count = mysql_num_rows($result);

    ?>


    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
        <tr class="TitleBar">
            <td colspan="2" class="TtlBarHeading">Regular Users (<?php echo $mydb->getCount('id', 'users','user_type="regular_user"');?>)
                <div style="float:right"></div>
            </td>
            <td class="TtlBarHeading" colspan="4" width="100%"><input name="search" type="text" id="usersearch" Placeholder="Search by Name" class="button" style="width:250px;"/></td>
            <td class="TtlBarHeading" style="width:50px;"><input name="btnAdd" type="button" value="Add" class="button" onClick="window.location='<?php echo ADMINURLPATH; ?>user_manage'"/>
            </td>

        </tr>
        <?php
        if ($count != 0) {
            ?>
            <tr>
                <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
                <td width="10%" class="titleBarB" align="center"><strong>Name</strong></td>
                <td width="20%" class="titleBarB" align="center"><strong>Email</strong></td>
                <td width="10%" class="titleBarB" align="center"><strong>Address</strong></td>
                <td width="15%" class="titleBarB" align="center"><strong>Contact Number</strong></td>
                 <td width="12%" class="titleBarB" align="center"><strong>Reference</strong></td>
                <td width="13%" class="titleBarB" align="center"><strong>Action</strong></td>
            </tr>
            <?php
            $counter = 0;
            while ($rasMember = $mydb->fetch_array($result)) {
                $gid = $rasMember['id'];
                ?>
                <tr id="userinfo<?php echo $rasMember['id']; ?>">
                    <td class="titleBarA" align="center" valign="top"><input name="eid[]" type="hidden" id="eid[]"
                                                                             value="<?php echo $rasMember['id']; ?>"/><?php echo ++$counter; ?>
                    </td>
                    <td class="titleBarA" valign="top"><?php echo stripslashes($rasMember['name']); ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['email']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['address']; ?></td>
                    <td class="titleBarA" valign="top"><?php echo $rasMember['phone'];?></td>
                     <td class="titleBarA" valign="top"><a href="<?php echo ADMINURLPATH; ?>referencelist&name=<?php echo $rasMember['reference']; ?>"><?php echo $rasMember['reference']; ?></a></td>
                    <!--<td class="titleBarA" valign="top"><img src="../upload/2015/06/<?php //echo $rasMember['filename'];
                    ?>" height="100" /></td>-->
                    <td class="titleBarA" valign="top" align="center"><a href="<?php echo ADMINURLPATH; ?>user_manage&id=<?php echo $rasMember['id']; ?>"><img src="images/action_edit.gif" alt="Edit" width="24" height="24" title="Edit"></a>
                        <a href="javascript:void(0);" onclick="callDelete('<?php echo $gid; ?>')"><img src="images/action_delete.gif" alt="Delete" width="24" height="24" title="Delete"></a>
                        <a href="<?php echo ADMINURLPATH; ?>gallery&id=<?php echo $rasMember['id']; ?>"><img src="images/arrow.png" alt="imagedisplay" width="24" height="24" title="Prescription_display"></a></td>
                </tr>

                <?php
            }

        } else {
            ?>
            <tr>

                <td class="message" colspan="4">No Users has been Added</td>
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
                $rs_result = $mydb->getCount('id', 'users','user_type="regular_user"');

                $total_pages = ceil($rs_result / $num_rec_per_page);

                if ($page <= 1)
                {

                }
                else
                {
                    $j = $page - 1;
                    echo '<li >
                            <a href="' . ADMINURLPATH . 'regular_user&page=' . $j . '" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>';
                }


                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i <> $page) {

                        echo '<li><a href ="' . ADMINURLPATH . 'regular_user&page=' . $i . '" >' . $i . '</a></li>';
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
                    <a href='" . ADMINURLPATH . 'regular_user&page=' . $j . "' aria-label='Next'>
                        <span aria-hidden='true'>&raquo;</span>
                    </a>
                </li>";
                }
                ?>
            </ul>
        </nav>

    <?php }
}?>



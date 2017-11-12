<?php
/**
 * @Author: Madmax
 * @Date:   2016-02-29 11:03:38
 * @Last Modified by:   Madmax
 * @Last Modified time: 2016-02-29 11:06:18
 */

if(isset($_GET['name']))
{
    $id=$_GET['name'];
    $result1 = $mydb->getQuery('*', 'users','reference="'.$id.'"');
?>

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="FormTbl" style="font-size:12px;">
        <tr class="TitleBar">
            <td colspan="9" class="TtlBarHeading">Users
                <div style="float:right"></div>
            </td>
          

        </tr>

        <tr>
            <td width="2%" valign="top" class="titleBarB" align="center"><strong>S.N</strong></td>
            <td width="10%" class="titleBarB" align="center"><strong>Name</strong></td>
            <td width="20%" class="titleBarB" align="center"><strong>Email</strong></td>
            <td width="10%" class="titleBarB" align="center"><strong>House Number</strong></td>
            <td width="10%" class="titleBarB" align="center"><strong>Street Name</strong></td>
            <td width="5%" class="titleBarB" align="center"><strong>Ward Number</strong></td>
            <td width="8%" class="titleBarB" align="center"><strong>Place</strong></td>
            <td width="8%" class="titleBarB" align="center"><strong>District</strong></td>
            <td width="15%" class="titleBarB" align="center"><strong>Contact Number</strong></td>
           
       
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
                <td class="titleBarA" valign="top"><?php echo stripslashes($rasMember['fullname']); ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['email']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['house_no']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['street_name']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['ward_no']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['place_name']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['district']; ?></td>
                <td class="titleBarA" valign="top"><?php echo $rasMember['mobile_number'];
                  
                    if($rasMember['landline_number'])
                    {
                          echo ','.$rasMember['landline_number'];
                    } 
                    ?>

                </td>
               
            </tr>
            <?php
        }
        ?>

    </table>

<?php  }


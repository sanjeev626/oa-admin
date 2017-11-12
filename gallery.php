<script type="text/javascript">
     function callDelete(gid)
     {
      if(confirm('Are you sure to delete ?'))
      {
          window.location='?manager=gallery&did='+gid;
      }
     }
</script>

 <?php 
 if(isset($_GET['did']))
{
    $delId = $_GET['did'];
    $clientid=$mydb->getValue('client_id','tbl_image','id='.$delId);
    $imagerem=$mydb->removeFile('tbl_image',$delId);
    
    $url=ADMINURLPATH."gallery&id=".$clientid;
    $mydb->redirect($url);
}
?>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="css/style.css" rel="stylesheet" type="text/css" >
    <script src="https://code.jquery.com/jquery-1.11.2.js"></script>

     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
    <!-- <script src="js/jquery1.11.2.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="jscript/bootstrap.min.js"></script>
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <h3 align="center">Prescription</h3>
        <div class="row">
           
            <?php 
                if(isset($_GET['id']))
                {
                    $getid=$_GET['id'];
                    $imageCount=$mydb->getCount('*','tbl_image','client_id='.$getid);
                  if($imageCount>0)
                  {
                    $username=$mydb->getValue('name','users','id='.$getid);

                  $rasMember=$mydb->getQuery('*','tbl_image','client_id='.$getid);
                    while($resMember=$mydb->fetch_array($rasMember))
                    {
                      $gid=$resMember['id'];
                       $imagename=$resMember['image_name'];
                       $imagepath=$resMember['image_path'];
                       $image_url = 'http://onlineaushadhi.com/storage/app/'.$imagename;
             ?>
         <div class="col-md-3">
            <img src="<?php echo $image_url;?>" alt="" width="250px;">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn" data-toggle="modal" data-target="#myModal<?php echo $resMember['id'];?>" style="font-size:14px;padding:8px;margin-left:20px;">
              Open 
            </button>
              <button type="button" class="btn btn-primary btn" data-toggle="modal" data-target="#myModal" style="font-size:14px;padding:8px;margin-left:20px;" onClick="callDelete('<?php echo $gid;?>')">
              Delete 
            </button>
            <!-- Modal -->
            <div class="modal fade" id="myModal<?php echo $resMember['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $username;?></h4>
                  </div>
                  <div class="modal-body">
                    <img src="<?php echo $image_url;?>" alt="" width="570px;">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div> 
          </div>
          <?php
                }
           }else
           {?>
           
              <div class="col-md-12" style="text-align:center;">
                <img src="<?php  echo SITEROOTADMIN;?>/images/Nopreview.jpg" alt="nopreview" width="250px;">
               </div>
         
          <?php }
          }
          ?>
        
          
        </div>

        <div class="row" style="margin:10px 0px">
         <button type="button" class="btn btn-primary btn" data-toggle="modal" data-target="#myModal" style="font-size:14px;padding:8px;margin-left:20px;" onClick="window.location='<?php echo ADMINURLPATH;?>users#<?php echo "userinfo".$getid;?>'">
              Back
            </button>
        </div>
    </div>   
    
        
      
   
    

  </body>
</html>
<?php
include "../../include/db.php";
include "../../include/authenticate.php"; if (!checkperm("c")) {exit ("Permission denied.");}
include "../../include/general.php";
include "../../include/collections_functions.php";

$use_local = getvalescaped('use_local', '') !== '';
$resource_type=getvalescaped('resource_type','');
$allowed_extensions=get_allowed_extensions_by_type($resource_type);

if ($use_local)
	{
	# File list from local upload directory.

	# We compute the folder name from the upload folder option.
	$folder = getAbsolutePath($local_ftp_upload_folder, true);

	if ($groupuploadfolders) // Test if we are using sub folders assigned to groups.
		{
		$folder.= DIRECTORY_SEPARATOR . $usergroup;
		}

	if (!file_exists($folder)) // If the upload folder does not exists, we try to create it.
		{
		mkdir($folder,0777);
		}

	// We list folder contents
	$files = getFolderContents($folder);
	}
else
	{
	# Connect to FTP server for file listing
	$ftp=@ftp_connect(getval("ftp_server",""));
	if ($ftp===false) {exit("FTP connection failed.");}
	ftp_login($ftp,getval("ftp_username",""),getval("ftp_password",""));
	ftp_pasv($ftp,true);

	$folder=getval("ftp_folder","");
	if (substr($folder,strlen($folder)-1,1)!="/") {$folder.="/";}
	$files=ftp_nlist($ftp,$folder);
	ftp_close($ftp);
	}
	
include "../../include/header.php";
?>
<div class="BasicsBox">
<h1><?php echo $lang["selectfiles"]?></h1>
<p><?php echo text("introtext")?></p>

<form method="post" action="team_batch_upload.php">
<input type="hidden" name="ftp_server" value="<?php echo getval("ftp_server","")?>">
<input type="hidden" name="ftp_username" value="<?php echo getval("ftp_username","")?>">
<input type="hidden" name="ftp_password" value="<?php echo getval("ftp_password","")?>">
<input type="hidden" name="ftp_folder" value="<?php echo getval("ftp_folder","")?>">
<input type="hidden" name="use_local" value="<?php echo getval("use_local","")?>">
<input type="hidden" name="no_exif" value="<?php echo getval("no_exif","")?>">

<div class="Question">
<label for="collection"><?php echo $lang["addtocollection"]?></label>
<select name="collection" id="collection" class="stdwidth"  onchange="if($(this).value==-1){$('collectionname').style.display='block';} else {$('collectionname').style.display='none';}">
	<option value="-1" <?php if ($upload_add_to_new_collection){ ?>selected <?php }?>>(<?php echo $lang["createnewcollection"]?>)</option>
	<option value="" <?php if (!$upload_add_to_new_collection){ ?>selected <?php }?>><?php echo $lang["batchdonotaddcollection"]?></option>
<?php
$list=get_user_collections($userref);
for ($n=0;$n<count($list);$n++)
	{
	?>
	<option value="<?php echo $list[$n]["ref"]?>"><?php echo htmlspecialchars($list[$n]["name"])?></option>
	<?php
	}?></select>
<div class="clearerleft"> </div>
<div name="collectionname" id="collectionname" <?php if ($upload_add_to_new_collection){ ?> style="display:block;"<?php } else { ?> style="display:none;"<?php } ?>>
	<label for="collection_add"><?php echo $lang["collectionname"]?></label>
	<input type=text id="entercolname" name="entercolname" class="stdwidth">
	</div>
</div>

<div class="Question"><label><?php echo $lang["selectfiles"]?></label>
<!--<div class="tickset">-->
<select name="uploadfiles[]" multiple size=20>
<?php 
for ($n=0;$n<count($files);$n++)
	{
	if ($use_local) {$fn=$files[$n];} else
		{
		# FTP - split up path
		$fs=explode("/",$files[$n]);
		if (count($fs)==1) {$fs=explode("\\",$files[$n]);} # Support backslashes
		$fn=$fs[count($fs)-1];
		}
	$show=true;
	if (($fn=="..") || ($fn==".")) {$show=false;}
	if (strpos($fn,".")===false) {$show=false;}
	if ($fn=="pspbrwse.jbf") {$show=false;} # Ignore PSP browse files (often imported by mistake)
	if ($fn==".DS_Store") {$show=false;} # Ignore .DS_Store file on the mac
	
	# omit disallowed extensions
	if ($allowed_extensions!=""){
	    $extension=explode(".",$fn);
		if(count($extension)>1){
    	$extension=trim(strtolower($extension[count($extension)-1]));
		} 
		if (!strstr($allowed_extensions,$extension)){$show=false;}
	}
	
	/* if ($show) { ?><div class="tick"><input type="checkbox" name="uploadfiles[]" value="<?php echo $fn?>" checked /><?php echo $fn?></div><?php } ?>
	*/
	if ($show) { ?><option value="<?php echo $fn?>" selected><?php echo $fn?></option><?php } ?>
	<?php
	}
?>
<!--</div>-->
</select>
<div class="clearerleft"> </div>
</div>

<div class="QuestionSubmit">
<label for="buttons"> </label>			
<input name="save" type="submit" value="&nbsp;&nbsp;<?php echo $lang["upload"]?>&nbsp;&nbsp;" />
</div>
</form>
</div>

<?php		
include "../../include/footer.php";
?>

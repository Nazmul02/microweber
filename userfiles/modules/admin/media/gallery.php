<?

$rand = rand();
//p($params);

if($params['page_id']){
	$for = 'content';
	$id = $params['page_id'];
}

if($params['post_id']){
	$for = 'content';
	$id = $params['post_id'];
}

if($params['category_id']){
	$for = 'category';
	$id = $params['category_id'];
}



if($for == false){
	
	$for = $params['for'];
}

if($id == false){
	
	$id = $params['for_id'];
}
 
?>

<input type="hidden" name="queue_id" value="<? print $rand ?>" />


 

<div id="media_manager<? print $rand ?>"></div>
<script>



var call_media_manager<? print $rand ?> = function(){
	
	
	//alert($id);
	
	 
	 data1 = {}
   data1.module = 'admin/media/media_manager';
    data1.for = '<? print $for ?>';
	data1.for_id = '<? print $id ?>';
	data1.queue_id = '<? print $rand ?>';
	//data1.type = 'picture';
 data1.type =  $("#media_type").val();
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: data1,

      async:true,

  success: function(resp) {

   $('#media_manager<? print $rand ?>').html(resp);

 

  }
    });
	
	
 
	
	
	
	
}



// ******************************** UPLOADER *******************************

function upload_by_embed(){
	
 
	
	
	
	embed_code =  $("#embed_code").val();
	screenshot_url =  $("#screenshot_url").val();
	original_link =  $("#original_link").val();
	media_type =  $("#media_type").val();
	
	media_name =  $("#media_name").val();
	media_description =  $("#media_description").val();
	
	
	
	
	$.post("<? print site_url('api/media/upload_to_library/'); ?>", { 'for':'<? print $for?>',  'for_id':'<? print $id?>',  'queue_id':'<? print $rand?>',media_name:media_name, media_description:media_description, embed_code: embed_code, media_type:media_type, screenshot_url: screenshot_url, original_link: original_link }  ,
   function(data) {
     //alert("Data Loaded: " + data);
	  call_media_manager<? print $rand ?>();
   });
	
	
	
 
 

}
$(document).ready(function(){
						   
						   call_media_manager<? print $rand ?>();
  // $(document.body).append('<div class="drag_files<? print $rand ?>"></div>');

     

 	$(".drag_files<? print $rand ?>").pluploadQueue({
		// General settings
		runtimes: 'html5,flash,gears,browserplus',
		url: "<? print site_url('api/media/upload_to_library/for:'.$for.'/for_id:'.$id.'/queue_id:'.$rand); ?>",
		max_file_size: '100mb',
		chunk_size: '1000mb',
		unique_names: true,


		resize: {width: 320, height: 240, quality: 90},


		filters: [
			{title: "Image files", extensions: "jpg,gif,png,bmp"},
			{title: "Zip files", extensions: "zip"}
		],


		flash_swf_url: '<?php   print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.flash.swf',
        silverlight_xap_url: '<?php print( ADMIN_STATIC_FILES_URL);  ?>js/plupload/js/plupload.silverlight.xap',
		preinit: {

		},


		init: {
         FilesAdded:function(up, files){

           this.start();

           /*$(".drag_files").css({
              width:0,
              height:0,
              left:-100,
              top:-100
            })  */
         },
         FileUploaded: function(up, file, info) {
			 call_media_manager<? print $rand ?>();
       //   var obj = eval("(" + info.response + ")");
            //$(document.body).append(obj.url);
        /*    var image = new Image();
            image.src = obj.url;
            mw.image.edit.init(image);
            $(".edit .plupload").remove();
            $(".edit_drag_curr").append(image);
             $(".drag_files").css({
              width:0,
              height:0,
              left:-100,
              top:-100
            })*/
         }
		}
	});


});

// ******************************** END UPLOADER *******************************

</script>





 media_type
 <select name="media_type" id="media_type" onchange="call_media_manager<? print $rand ?>()">
  <option value="picture">picture</option>
 <option value="video">video</option>
 </select>




<div class="drag_files<? print $rand ?> drag_files_here">drag files here</div>
<div class="embed_media">Add media by url or embed code:
  <div class="c">&nbsp;</div>
 
  <div class="video_embed">
    <label>Paste link</label>
    <textarea style="height: 53px;padding: 2px"  onchange="parse_embeds();" id="original_link" name="original_link"></textarea>
    <label>Paste the video embed code</label>
    <span>
    <textarea style="height: 53px;padding: 2px"  onchange="parse_embeds();" name="embed_code" id="embed_code"><? print $the_post['custom_fields']['embed_code']; ?></textarea>
    </span> </div>
  screenshot_url <input    name="screenshot_url" id="screenshot_url"   type="text" value=""  />
  <br />
 media_name <input    name="media_name" id="media_name"   type="text" value=""  />
  media_description <input    name="media_description" id="media_description"   type="text" value=""  />
  
 
  
  
  <input type="button" name="save" value="save" onclick="upload_by_embed()" />
  <input type="button" name="refresh" value="refresh" onclick=" call_media_manager<? print $rand ?>();" />
  <script type="text/javascript">
        $(document).ready(function() {
          	 if($("#embed_code").val()!=''){
                  parse_embeds();
          	 }
        });
		function parse_embeds(){

		q = $("#original_link").val();


        if(q.indexOf("<object")==-1 || q.indexOf("<embed")==-1 || q.indexOf("<iframe")==-1){


        if($.isEmbedly(q)){
    		$.embedly(q, { /*maxWidth: 600, wrapElement: false*/ }, function(oembed, dict){

    			if (oembed == null){
    				//$("#embed").html('<p class="text"> Not A Valid URL </p>');
                     $(".video_link").hide();
                     $(".video_embed").show();

    			}else {
    				$("#embed_code").val(oembed.code);
    				$("#screenshot_url").val(oembed.thumbnail_url);
                    //$(".video_preview").html("<img src='" + oembed.thumbnail_url + "' />");
                    $(".video_preview").html(oembed.code);
                    $("#media_name").val(oembed.title);
                   $("#media_description").val(oembed.description);
    			}
    		});
        }
        else{
             //$(".video_link").hide();
             //$(".video_embed").show();
             mw.box.alert("Video was not found on this address, please enter the embed code.")


        }

        }
         if(q.indexOf("<object")!=-1 || q.indexOf("<embed")!=-1 || q.indexOf("<iframe")!=-1){
           //alert(q.indexOf("<object"))
          //$(".video_preview").html(q)
        }



}
</script>
  <div class="video_preview"> </div>
 
</div>

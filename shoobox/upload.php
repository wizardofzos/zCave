<?
if ( !empty( $_GET['n'] ) )	
{
	$fd = fopen("php://input", "r") or die("error");
	while( $data = fread( $fd, 5000000 ) ) file_put_contents( "/home/hktools/uploaded/{$_GET['n']}", $data, FILE_APPEND );
}

?>

<script type="text/javascript">


 
window.onload = function() {
  if (!window.google || !google.gears) {
    addStatus('Gears is not installed', 'error');
    return;
  }
}

/**
 * Display information to the client
 */
function _addStatus(s){ $("#status").append( s + "<br />" ); return 1;}
function addStatus(s){ return 1;}

/**
 * Get the minimum of two results.
 */
function min(a,b){ return (a<b?a:b); }


/**
 * Open file browser window
 */
gearsupload = {
	mylist: [],
	fileName: "",
	CHUNK_BYTES: 100000, 	// Send file in packets of 200KB
	MAX_FILE_SIZE: 5000000,// Limit the total upload size to 5MB
	UPLOAD_RETRIES: 3,		// Number of retries	
	
	browse: function (){
		var desktop = google.gears.factory.create('beta.desktop');
		
		desktop.openFiles(
			function(callback, that){
				return	function(){
					return	callback.apply(that, arguments);
				}
			}
			(function(files) {
			
				for ( var i = 0; i < files.length; i++ )
				{
					if ( this.mylist[files[i].name] ){ continue; } // Has the file by the same name already been selected?
					
					this.mylist[files[i].name] = {
						filename:	files[i].name, 
						uploaded:	0,
						length: 	files[i].blob.length, 
						blob:		files[i].blob, 
						bytesUploaded: 0,
						status:		(files[i].blob.length>this.MAX_FILE_SIZE?"File too large":"Pending")};
					
					$("#selectedText").append("Selected: " + files[i].name + " " + files[i].blob.length) + "<br />";
					addStatus( "Selected: " + files[i].name + " " + files[i].blob.length );
				}
				// $('#upload').html('<a href="#upload" onclick="return gearsupload.upload();">Upload</a>');
				gearsupload.upload();
			},
			this)
		,
	    {  }
	    //  { singleFile: true }
		);	

	},
	
	upload: function()
	{
		var chunkLength, chunk, mylist = this.mylist;
	
		/**
		 * Loop through the files and upload the next file/chunk
		 */
	
		for ( file in mylist ) if ( ( mylist[file].uploaded < mylist[file].length && !mylist[file].error ) )
		{
			/**
			 * what is the current filename
			 */
			fileName = file;
			chunkLength = min( mylist[file].uploaded + this.CHUNK_BYTES, mylist[file].length);
			addStatus( "Uploading " + fileName + ": from " + mylist[file].uploaded + " to " + chunkLength );
	
			/**
			 * Get the next chunk to send.
			 */
			chunk = mylist[file].blob.slice( mylist[file].uploaded, (chunkLength - mylist[file].uploaded) );
			addStatus( "Chunk length " + chunk.length );
			
			/**
			 * Send Chunk
			 */
			this.sendChunk( mylist[file], chunk, mylist[file].uploaded, chunkLength, mylist[file].length );
			break;
		}
	},
	
	
	sendChunk: function ( entry, chunk, start, end, total )
	{
		var req = google.gears.factory.create('beta.httprequest');
		var prcnt = Math.ceil( ( end/total ) * 100 );
		/**
		 * Start Post
		 */
		req.open('POST', 'http://hktools.office-on-the.net/upload.php?n='+encodeURIComponent(fileName)+'&b='+encodeURIComponent(start) );
	
		/**
		 * Assign Headers
		 */
		var h = { 'Content-Disposition'	: 'attachment; filename="' + fileName + '"', 
						'Content-Type' 	: 'application/octet-stream',
						'Content-Range'	: 'bytes ' + start + '-' + end + '/' + total };
		
		for( var x in h ) if (h.hasOwnProperty(x)) { req.setRequestHeader( x, h[x] ); }
		
		/**
		 * Build Response function
		 */
		req.onreadystatechange = function(callback, that){
				return	function(){
					return	callback.apply(that, arguments);
				}
			}
			(function(){
			if (req.readyState == 4 && addStatus( "Resp: (" + req.status + ")" ) && req.status == 200 ) {
				entry.uploaded = end;
				addStatus( fileName + ( (end + 1) >= total ? " Finished" : ' Upload: so far ' + prcnt + '%' ) );
				$("#progressText").html(prcnt + '%');
				this.upload();
			}
		},this);
	
		/**
		 * Send Chunk
		 */
		req.send(chunk);
	}
};

gearsupload.browse();

</script>
<span id="upload"></span>
<div id="selectedText"></div>
<div id="progressText">Select file(s)</div>
<p id="status"></p>
<div id="progressText"></div>
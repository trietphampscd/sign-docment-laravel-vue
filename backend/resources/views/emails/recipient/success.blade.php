<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
    <style type="text/css">
		.content-card {
			width: 80%;
			border-radius: 3px;
			-webkit-box-shadow: 0 3px 6px 0 rgba(32,20,4,.05);
			box-shadow: 0 3px 6px 0 rgba(32,20,4,.05);
			background-color: #fff;
			padding: 15px;
			margin-bottom: 10px;
			margin: 0px auto;
		}
		.comments {
		    line-height: 1.5;
		    letter-spacing: normal;
		    text-align: left;
		    color: #9ea0a5;
		    word-break: break-word;
		}
		.doc-format {
		    border: 1px solid #ebebeb;
		    margin-bottom: 30px;
		}
		.doc-format .doc-header {
		    padding: 15px 30px;
		    display: -webkit-box;
		    display: -ms-flexbox;
		    display: flex;
		    -ms-flex-wrap: wrap;
		    flex-wrap: wrap;
		    -webkit-box-pack: justify;
		    -ms-flex-pack: justify;
		    justify-content: space-between;
		    -webkit-box-align: center;
		    -ms-flex-align: center;
		    align-items: center;
		    border-top: 2px solid #ebebeb;
		    border-bottom: 1px solid #ebebeb;
		}
		mb-1, .my-1 {
		    margin-bottom: .25rem!important;
		}
		img {
		    border-style: none;
		}
		img, svg {
		    vertical-align: middle;
		}		
		.doc-format .doc-content {
		    padding: 30px;
		}
		.doc-format .doc-title {
		    font-size: 26px;
		    font-weight: 700;
		    line-height: 1.33;
		    text-align: center;
		}
	    .text-center {
	        text-align: center!important;
	    }
	    .mb-4, .my-4 {
	        margin-bottom: 1.5rem!important;
	    }
	    .mt-3, .my-3 {
	        margin-top: 1rem!important;
	    }
	    .doc-format .doc-item {
	        padding: 15.5px 0;
	        border-bottom: 1px solid #f3f3f3;
	    }  
	    .doc-format .doc-top-border {
	        border-top: 1px solid #f3f3f3;
	    }
	    .min-width-230px {
	        min-width: 230px;
	    }  
	    .ml-3, .mx-3 {
	        margin-left: 1rem!important;
	    }
		.doc-format .signer {
		    padding: 8px 0;
		}
		.doc-format .doc-item {
		    padding: 15.5px 0;
		    border-bottom: 1px solid #f3f3f3;
		}
		.doc-format .doc-top-border {
		    border-top: 1px solid #f3f3f3;
		}
		.d-flex {
		    display: -webkit-box!important;
		    display: -ms-flexbox!important;
		    display: flex!important;
		}
		.border-bottom-0 {
		    border-bottom: 0!important;
		}
				.comments {
		    line-height: 1.5;
		    letter-spacing: normal;
		    text-align: left;
		    color: #9ea0a5;
		    word-break: break-word;
		}
		.min-width-124px {
		    min-width: 120px;
		}
		.btn {
		  height: 40px;
		}
		.mt-4, .my-4 {
		    margin-top: 1.5rem!important;
		}
	    .btn-block {
	        display: block;
	        width: 100%;
	    }
	    .btn-secondary {
	        color: #23282c;
	        background-color: #c8ced3;
	        border-color: #c8ced3;
	    }
	    .btn {
	        display: inline-block;
	        font-weight: 400;
	        color: #23282c;
	        text-align: center;
	        vertical-align: middle;
	        -webkit-user-select: none;
	        -moz-user-select: none;
	        -ms-user-select: none;
	        user-select: none;
	        background-color: transparent;
	        border: 1px solid transparent;
	        padding: .375rem .75rem;
	        font-size: .875rem;
	        line-height: 1.5;
	        border-radius: .25rem;
	        -webkit-transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
	        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
	        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
	        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
	    }
	    .padding_top_3{
	    	padding-top: 3px;
	    }
		.doc-subtitle {
		    font-size: 16px;
		    font-weight: 500;
		    line-height: 1.56;
		    color: #2e221e;
		    margin-bottom: 15px;
		}	 
		.doc-text {
		    font-size: 14px;
		    line-height: 1.43;
		    color: #9ea0a5;
		}
		.mb-4, .my-4 {
		    margin-bottom: 1.5rem!important;
		}	

		.doc-footer {
		    text-align: center;
		}
		.doc-text {
		    font-size: 14px;
		    line-height: 1.43;
		    color: #9ea0a5;
		}			   
    </style>
  </head>
  <body>
	<div class="col-12 col-lg-8">
		<div class="content-card">
	  		<div class="doc-format" style="border-color: rgb(46, 57, 73);">
			     <div class="doc-header" style="border-color: rgb(46, 57, 73);">
			     	<div style="width:100%; display:inline-block; text-align: center">
			     		<img src="{{$info['url_root']}}/img/logo_dark.jpg" alt="img" width="125" class="my-1">
					</div>
			     	<div style="display: none">
					 	<div style="width:50%; float:left; display:inline-block">
				     		<img src="{{$info['url_root']}}/img/logo_dark.jpg" alt="img" width="125" class="my-1">
						</div>
						<div style="width:50%; float:right; display:inline-block; text-align:right;">
				     		<img src="{{$info['url_root']}}/img/profile/logo_axisbits_sm.png" alt="img" class="my-1">
						</div>	
			     	</div>
			     </div>
			     <div class="doc-content">
			        <div class="doc-title">You got the Signing request</div>
			        <div class="text-center mt-3 mb-4">
			           Press button below to go to the signing page.
			        </div>
			        <div class="doc-item doc-top-border">
			        	<span class="min-width-230px">Document name</span>
			        	<span class="ml-3">{{$info['subject']}}</span>
			        </div>
					@foreach($info['recipients'] as $recipient)
			        <div class="doc-item doc-top-border d-flex border-bottom-0 signer">
			           <div class="comments min-width-124px">Signer {{$loop->iteration}}:</div>
			           <span class="padding_top_3">{{$recipient['name']}} ({{$recipient['email']}})</span>
			        </div>
					@endforeach
					<div style="padding: 15px 0">{{ $info['message'] }}</div>
			        <button type="button" class="btn mt-4 btn-secondary btn-block" style="background-color: rgb(98, 153, 248); color: #fff;"><a href='{{ $info['url_document'] }}' style="text-decoration: none; color: #fff;">Checking and Signing Content</a></button>
			    </div>
		  	</div>
			<div class="doc-content">
				<div class="doc-subtitle">Do not share links with email.</div>
				<div class="doc-text mb-4">
					If you share a link with this message, unauthorized third parties can verify and sign the content. Signing is not responsible for any problems that occur at this time.
				</div>
			</div>
			<div class="doc-footer doc-text">
				This is send-only. Please do not reply. <br>© 2019. Coffeesign Inc. All rights reserved. 
			</div>		  	
		</div>
	</div>
  </body>
</html>
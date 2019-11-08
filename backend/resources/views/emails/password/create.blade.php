 <html lang="en-US">
   <head>
     <meta charset="utf-8">
   </head>
   <body>
		<h3>Reset password request</h3><br/>
		<p>You're receiving this Email because you have requested reset password action.</p>
		<a href="{{ Config::get('constants.FRONTEND_URL').'/password/reset/'.$data['token'] }}">Reset Password</a>
		<p>CoffeeSign Team</p>
   </body>
 </html>
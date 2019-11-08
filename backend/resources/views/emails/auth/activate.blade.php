<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h3>Confirm Account</h3><br/>
    <p>Thanks for signup! Please before you begin, you have to confirm your account.</p>
    <a href="{{ Config::get('constants.FRONTEND_URL').'/signup/confirm/'.$data['activation_token'] }}">Activate</a>
    <p>CoffeeSign Team</p>
  </body>
</html>
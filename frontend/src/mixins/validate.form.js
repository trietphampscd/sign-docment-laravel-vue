
export const checkForm = {
  methods: {
  	isRequired(value, error_flag)
  	{
		  return (
		  	error_flag &&
		    ( value === undefined ||
		      value === null ||
		      (typeof value === "object" && Object.keys(value).length === 0) ||
		      (typeof value === "string" && value.trim().length === 0)
		    )
		  );
	 	},
	 	isEmail( email ) 
	 	{
	 		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
	 	},
	 	isCheckEmailClient(email, obEmailClient)
	 	{
			var _isEmail = false;
	 		obEmailClient.map((value, key) => {
	 			if(email.trim() === value.email.trim()) {
					return _isEmail = true;
				} 
	 		})
	 		return _isEmail;
	 	},
	 	isConfirmPass(pass, confirmPass)
	 	{
	 		// console.log(pass, confirmPass)
	 		if(pass === confirmPass) 
	 			return true;
	 		return false
	 	}
	}
}

export default { checkForm };

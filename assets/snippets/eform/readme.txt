eForm 1.0 (Electronic Form Snippet)
Created By: Raymond Irving 15-Dec-2004.
-----------------------------------------------------


Usage: 	
Converts or save a web form into a email or html document
This new document can be sent via email to specified user
Main Features:form mail and save,auto-repond, report generation

	Params:
	&vericode (Optional)
		Enables verification code. The template contain a vericode 
		field and a [+vericode+] placeholder

	&formid
		This is a unique id used to identity a form during postbacks
		This is useful in cases where more than one form exists on 
		a page

	&to (Optional)
		Email address to send eForm information to
		For multiple receipent separate emails with a comma (,)
		If omitted the site settings email address will be used

	&from (Optional)
		Sets he email address to appear in the From section of the email

	&fromname (Optional)
		Sets the name of the sender in the From section of the email

	&sendirect (Optional) 
		This will use the form field called email to supply the
		email address to send the message to.
		Set to 1 to activate send direct. Defaults to 0

	&cc (Optional)
		Same as &email but for Cc:

	&bcc (Optional)
		Same as &email but for Bcc:

	&ccsender (Optional)
		Set to 1 to send the user a copy of the submitted form. Defaults to 0
		eForm will look for the user's email address inside a field called email.

	&subject (Optional)
		Subject to appear in email of datatile
		Can include [.form fields.]. E.g. Purcase Order for [.firstname.] [.lastname.]

	&noemail (Optional)
		Prevents eform from sending emails e.g. no-reply@mydomain.com
		Set to 1 to disable emails. Defaults to 0

	&mailselector (Optional)
		Sets the name of the form field to use as a selector to select
		a single email from the comma (,) delimited emails assigned 
		the &to parameter. This selector field will act like a numeric 
		index to select an email. It will start at 1 for the first email 
		and ends at N for the last email in the list.

		For example:
			&to =`sales@me.com,support@me.com,billing@me.com`
			&mailselector=`topic`

			on the web form the topic fields is actually a dropdown menu.
			when the selects a topic from the list the value 1,2 or 3 will 
			be sent to eForm which will then be used to select one of the 
			three emails assigned to the &to parameter. This email address
			will be the address used to send the email to.

	&mobile (Optional)
		Mobile email address. This email is used to send a short 
		notification message to a mobile device.

	&mobiletext (Optional)
		Text message to send to mobile device
		Can include [.form fields.]. E.g. Order for [.firstname.]

	&gotoid	(Optional) 
		document id to load after sending message

	&category (Optional)
		Category ID or name used to categorize eForms. 
		If category is not found a new category will beb created
		This will appear in the subject of the email sent to the user

	&keywords (Optional)
		Comma delimited keywords or [.form fields.] used when searching databank
		E.g. [.firstname.], [.lastname.], [.email.]

	&autosender (Optional)
		email to display as sender of the auto-respond message
		e.g. no-reply@mydomain.com

	&automessage (Optional)
		chunk name (non-numeric) or document id (numeric) to use as an auto-responder message
		Can include [form fields]. E.g. [firstname]
		- tags: [form_fields],[postdate] 
		- note: eForm will send the auto-respond message to the email address specified 
				inside the [email] form field. 

	&tpl (Optional)
		chunk name (non-numeric) or document id (numeric) to use as a template
		- tags: [+form_fields+],[+validatemessage+], [+postdate+]
				where form_fields is the name of the field used in a form

	&report (Optional)
		chunk name (non-numeric) or document id (numeric) to use when 
		generating reports

	&allowhtml (Optional)
		Set to 1 to allow user to enter html tags. Defaults to 0

	&format (Optional)
		list of form fields that requires server-side validation
		- format: field_name:field_description:field_datatype:field_required
			- field_required: 0 or 1
			- field_datatype:
				string, 
				date, 
				integer, 
				float, 
				email, 
				file		- for file upload input
				listbox		- for <select> boxes, 
				checkbox	- for <input checkbox> 
				radio		- for <input radio>, 
				html 		- will converts \n to <br />
			- Note: for listbox, checkbox, radio  use [.field_name:field_value.] in order to reselect the correct item
		- Example: txtname:Name:string:1,txtage:Age:number:0,txtdob:Date Of Birth:date:0


Basic Examples:
[[eForm?&to=me@mydomain.com&gotoid=1&tpl=orders]] 

[[eForm? 
	&to=`sales@mysuppliers.com`
	&gotoid=`7` 
	&category = `Purchase Order`
	&tpl=`purchase_order`
]] 

Example using a contact us form:

[[eForm? &formid=`ContactForm` &to=`sales@me.com` &mailselector=`destination` &tpl=`ContactForm` &report=`ContactReport` &category=`Contact Requests` &format=`destination:Department:string:1,topic:Topic:string:1,name:Name:string:1,email:Email:email:1,message:Message:html:1`]]


For Simple form:
[[eForm? &vericode=`1` &formid=`ContactForm` &to=`sales@me.com` &gotoid=`1` &tpl=`ContactForm` &report=`ContactReport` &category=`Contact Requests` &format=`subject:Subject:string:1,name:Name:string:1,email:Email:email:1,message:Message:html:1`]]


PHP Event Function:
$eFormOnMailSent
	This function is called after email has been sent

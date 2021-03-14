function check_token(){
	var document_cookies = document.cookie.split(';');
	var cookies = {};
	document_cookies.forEach((cookie)=>{
		var c = cookie.split('=');
		let k = c[0];
		let v = c[1];
		cookies[k.trim()] = v;
	});

	if(cookies['_token']===null){
		return null;
	}
	return cookies['_token'];

}

// get and load the Client Data
function ClientData(_token){
	$.ajax({
		"url": "./api.php/clients",
		  "method": "GET",
		  "timeout": 0,
		  "headers": {
		    "token": _token
		  },
		success: function(d,m){
			// Store the data locally
			localStorage.setItem('data',JSON.stringify(d));
		}
	});
}

// Add New client usin modal
function AddNewClient (_token){

	let newClientName, newClientisBlacklisted,newClientisBusiness;
	newClientName = document.getElementById('newClientName').value;
	newClientisBlacklisted = document.getElementById('isBlacklisted').value;
	newClientisBusiness = document.getElementById('isBusiness').value;

	// add form data and submit form if the all fields are filled
	var form = new FormData();
	form.append("account_name", newClientName);
	form.append("isBlacklisted", newClientisBlacklisted);
	form.append("isBusiness", newClientisBusiness);

	var settings = {
	  "url": "./api.php/add_client",
	  "method": "POST",
	  "timeout": 0,
	  "headers": {
	    "token": _token,
	  },
	  "processData": false,
	  "mimeType": "multipart/form-data",
	  "contentType": false,
	  "data": form
	};
	$.ajax(settings).done(function (response) {
	  init();
	  document.getElementById('newClientName').value = '';
	});
}

function writeToAccountModal(){

	var data = JSON.parse(localStorage.getItem('current_acc'));

 	var id = document.getElementById('account_id');
 	var account_num = document.getElementById('account_num');
 	var amount = document.getElementById('account_amount');
 	var status = document.getElementById('account_status');

 	id.innerText = data['id'];
 	account_num.innerHTML = '<b>'+data.account_num;+'</b>';
 	status.innerHTML = data.isBlacklisted==0?'<span class="badge badge-success" >Not BlackListed</span>':'<span class="badge badge-danger" >BlackListed</span>';

 	if(data.amount_owed){
 		amount.innerHTML = '<b> <span class="badge badge-warning" ><i class="fas fa-funnel-dollar"></i> </span> '+data.amount_owed+'</b>';
 		$('.depositForm').show();
 	}else{
 		$('.depositForm').hide();
 	}
 	// console.log(data);
}

function deleteClient(account_num,_token){

	var link = "./api.php/deleteClient/"+account_num;

	$.ajax({
		"url": link,
	  "method": "DELETE",
	  "timeout": 0,
	  "headers": {
	    "token": _token,
	  },
	  "processData": false,
	  "contentType": false,
	  success: function (d,m){}
	});

}

// Blacklist Client

function blacklistClient(account_num,_token){
	var link = "./api.php/blacklistClient/"+account_num;

	$.ajax({
		"url": link,
	  "method": "GET",
	  "timeout": 0,
	  "headers": {
	    "token": _token,
	  },
	  "processData": false,
	  "contentType": false,
	  success: function (d,m){}
	});
}

// Get Account details
function AccountDetails(account_num,_token){
	var link ="./api.php/client_acc/"+account_num;
	$.ajax({
		"url": link,
	  "method": "GET",
	  "timeout": 0,
	  "headers": {
	    "token": _token,
	  },
	  "processData": false,
	  "contentType": false,

	}).done(function(resp){
		localStorage.removeItem('current_acc');
		localStorage.setItem('current_acc',JSON.stringify(resp));
		writeToAccountModal();
	});

}

function depositAmount(_token){
	var amount = document.getElementById('depositAmount').value;

	var form = new FormData();
	form.append("account_num", JSON.parse(localStorage.getItem('current_acc'))['account_num']);
	form.append('amount',amount);

	if(amount<1){
		alert("You can not deposit a NOTHING.");
	}

	var settings = {
	  "url": "./api.php/debt_deposit",
	  "method": "POST",
	  "timeout": 0,
	  "headers": {
	    "token": _token,
	  },
	  "processData": false,
	  "mimeType": "multipart/form-data",
	  "contentType": false,
	  "data": form
	};

	$.ajax(settings).done(function (response) {
	  init();
	});

}

function init(){
	(()=>{
		var tbody = document.querySelector("#clients_table tbody");
		tbody.innerHTML = null;
		var storage = JSON.parse(localStorage.getItem('data'));
		if(storage==null){
			Alert("No Client Data was fount");
		}
		// append to the table
		storage.forEach((row)=>{
			let el = document.createElement('TR');
			var cols = [
				row.id,
				'<span><a>'+row.account_name+'</a></span>',
				'<span><a class="link acc_num" data-toggle="modal" data-target="#client-details-modal" >'+row.account_num+'</a></span>',
				row.institution,
				row.isBusiness,
				row.isBlacklisted,
				row.date_blacklisted,
				row.manager,
				'<button class="btn btn-danger btn-sm" data-account="'+row.account_num+'" id="deleteClient"> <i class="fa fa-trash" ></i> </button>'+
				'<button class="btn btn-warning btn-sm" data-account="'+row.account_num+'" id="blacklistClient"> <i class="fa fa-user-slash text-light"></i> </button>'
			];
			cols.forEach((col)=>{
				var c = document.createElement('TD');
				c.innerHTML = col;
				//append each newly created element to the new tr element
				el.appendChild(c);
			});
			tbody.appendChild(el);
		});
	})();
}

$(document).ready(function(){
	// _get token
	var _token = check_token();
	// Load the clients data
	var Clients = ClientData(_token);

	// Add whatever client data brought and stored from the server
	init();

	$('#deposit').click(function(){
		depositAmount(_token);
	});

	// On account number click
	$('.acc_num').click(function(){
		document.getElementById('account_amount').innerHTML = '';
		var acc_num = this.innerText;
		AccountDetails(acc_num,_token);
	});

	// add new client btn click
	$('#addClientModal').click(function(){
		AddNewClient(_token);
	})

	// Refresh Clients table
	$("#refreshClientsBTN").click(function(){
		console.log("Table Refresh");
		init();
	});

	$('button#deleteClient').click(function(){
		let account_num  = this.attributes['data-account'].nodeValue;

		var r=confirm("Are you sure you want to Delete this Client.\nAll actions are irreversable.");
		if (r==true){
			deleteClient(account_num,_token);
			init();
	  	}
		else{
		  	x="You pressed Cancel!";
		}

	});
	$('button#blacklistClient').click(function(){
		let account_num  = this.attributes['data-account'].nodeValue;

		var r=confirm("Are you sure you want to Blacklist this Client.\nAll actions are irreversable.");
		if (r==true){
			blacklistClient(account_num,_token);
			init();
	  	}
		else{
		  	x="You pressed Cancel!";
		}

	});

	// Set the table as a data Table
	$('#clients_table').DataTable();
});
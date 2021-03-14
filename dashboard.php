<?php
include('./linker.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title></title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />

	<!-- Stylesheet -->
	<link rel="stylesheet" type="text/css" href="./assets/dashboard.css">
</head>
<body>
	<main class="container">
		<section class="content" >
			<div class="container-fluid pt-5">
				<div class="row">
					<div class="col-md-3 mt-2 justify-content-right">
						<div class="btn-group btn-group-md">
							<button class="btn btn-primary" data-toggle='modal' data-target="#add-client-modal" >
								<i class="fas fa-user-plus" ></i>
								Client
							</button>
							<button class="btn btn-primary" id="refreshClientsBTN">
								<i class="fas fa-table" ></i>
								Refress Table
							</button>
						</div>
					</div>
				</div>
			</div>

			<hr>
			<table class="table table-color teable-responsive" id="clients_table">
				<caption>
					<h2>Clients</h2>
				</caption>
				<thead>
					<th>#</th>
					<th>Acc Name</th>
					<th>Acc Num</th>
					<th>Institution</th>
					<th>Type</th>
					<th>Blacklisted</th>
					<th>Date Blacklisted</th>
					<th>Manager</th>
					<th>***</th>
				</thead>
				<tbody>
					
				</tbody>
			</table>

		</section>
	</main>
<!-- MODALS -->
<!-- Add Client modal -->
<div class="modal fade" id="add-client-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>ADD Client</h4>
			</div>
			<div class="modal-body">
				<div class="form">
					
					<div class="form-group">
						<input type="text" class="form-control" name='newClientName' id="newClientName" placeholder="Name">
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="isBusiness">
						<label class="form-check-label" for="flexCheckDefault">
							Business.
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="isBlacklisted">
						<label class="form-check-label" for="flexCheckDefault">
							Blacklist Client.
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success btn-sm" id="addClientModal"> Add </button>
				<button class="btn btn-danger btn-sm" data-dismiss="modal"> Close </button>
			</div>
		</div>
	</div>
</div>

<!-- Client details modal -->
<div class="modal fade" id="client-details-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>ADD Client</h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<div class="row">
						
						<div class="col-12">
							<h3 align="right" >Account Detials</h3>
						</div>			
						 <div class="col-4">
						 	ID
						 </div>		
						 <div class="col-8" id="account_id">
						 	
						 </div>

						 <div class="col-4">
						 	Account #.
						 </div>		
						 <div class="col-8" id="account_num">
						 </div>

						 <div class="col-4" >
						 	Amount Owed.
						 </div>		
						 <div class="col-8" id="account_amount">
						 </div>

						 <div class="col-4">
						 	Status.
						 </div>		
						 <div class="col-8" id="account_status">
						 </div>
						 <hr>
						 <div class="form depositForm" >
						 	<div class="row g-3 align-items-center">
								<div class="col-auto">
									<label for="deposit" class="col-form-label">Deposit : </label>
								</div>
								<div class="col-auto">
									<input type="number" id="depositAmount" class="form-control">
								</div>
								<div class="col-auto">
									<button class="btn btn-success" id="deposit"> <i class="fas fa-hand-holding-usd" >  </i> SEND </button>
								</div>
							</div>
						 </div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger btn-sm" data-dismiss="modal"> Close </button>
			</div>
		</div>
	</div>
</div>


</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>

<script src="./assets/dashboard.js" type="text/javascript"></script>


<script type="text/javascript">
	$("#menu-toggle").on('click',function (){
		$("aside").toggleClass('collapsed');
		$(".content").toggleClass('expanded');
	});
</script>

</html>
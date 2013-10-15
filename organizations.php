<?php 
  $PAGE_TITLE = "Organizations";
  require 'header.php'; 
?>

<form id="edit-organization-form" class="my-form">
	<fieldset>
		<div class="row">
			<div class="col-md-12">
				<h2>Organizations</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<p>Organizations affect two major parts of the conference app. Each attendee will be associated with an organization. This is corrispond to how they are organized in the directory page. Each Organization will be it's own catragory in the directory to filter by.</p>
			</div>
			<div class="col-md-6">
				<label for="inputInterests">Select A Organizations</label>
			    <div class="form-group">
			        <select class="form-control" id="select-organization" style="margin-right:10px;">
			         	<option value="">Chose One!</option>
			        </select><br>
			        <button class="btn btn-primary" type="button" id="editorganization" >Edit Organization</button>
			    </div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6" id="editOrg" style="display:none;">
				<div class="form-group">
					<label>Name</label>
					<input type="text" id="organizationName" class="form-control">
					<br>
					<input type="submit" value="Update Organization Name" class="btn btn-medium btn-success" id="add-presentation" >
				</div>
			</div>
			<div class="col-md-6">
			</div>
		</div>
	</fieldset>
</form>

<div class="row">
	<div class="col-md-12">
		<h3>Organizations</h3>
		<table class="table" id="organizationTable">
	        <thead>
	          <tr>
	            <th>Name</th>
	            <th width="10%">edit</th>
	          </tr>
	        </thead>
	        <tbody>
	        </tbody>
	      </table>
	</div>
</div>

<?php require 'modal.php'; ?>

<script type="text/javascript">
  	Parse.initialize("bzxMfkPDky6xy8G6rTjH39N2GG3U08G6NaSjPTLg", "WuQ4EQv7CW1IVX8wrdOnTOYeEUovU0vqWOGZxZfp");
	$(document).ready(function() {
	
		//Pulls Persons Object from DB
	    var Organization = Parse.Object.extend("Organization");
	    var organizationQuery = new Parse.Query(Organization);

	    //arrays
	    var organizationsArray = [];

	    var thisOrganization;

	    //grabs organizations and populates the select
	    organizationQuery.ascending("name").find({
			success: function(results) {
				organizationsArray = results;
			  	for(var i = 0; i < results.length; i++) {
				    var 
				      	organization = results[i],
						id = organization.id,
						name = organization.get('name'),
						table = $('#organizationTable tbody'),
						deleteBtn = $('.btn-delete'),
						select = $('#select-organization')
				    ;
				    
				    select.append('<option value="' +  id + '">' + name + '</option>');

					table.append('<tr><td>' + name + '</td><td><button class="btn btn-small btn-danger btn-delete" id="' +  id + '">remove</button></td><td>');

			  	}
			  	deleteBtn.on('click', function(){
			    	var
			    		organizationId = $(this).attr('id')
			    	;
			    	deleteorganization(organizationId);
			    });
			},
			error: function(error) {
			  alert("Error: " + error.code + " " + error.message);
			}
		});

		function deleteorganization(organizationId){
	  		organizationQuery.get(organizationId, {
				success: function(organizationId) {
					organizationId.destroy({});
					$('#modal-success').modal('show');
					$('#modal-success .modal-body').html('<p>You Successfully deleted the person you did not seem to like anyway. Good going!</p>');
				},
				error: function(object, error) {
					$('#modal-fail').modal('show');
				}
			});
	    }

	     /***************
	    ****************
		Populate for to edit organization
		****************
	    ***************/

	    $('#editorganization').on('click', editorganization);
	    function editorganization(){
	    	var
			  entityId =  $("#select-organization option:selected").val()
			;
			populateForm(entityId);
			$('#editOrg').show();
	    }
	    function populateForm(entityId){
			organizationQuery.get(entityId, {
				success: function(entity) {
					var 
						nameInput = entity.get("name")
		      		;
		      		//sets var for resource to edit
					thisOrganization = entity;

		      		$("#organizationName").val(nameInput);

		      	},
				error: function(object, error) {

					$('#modal-fail').modal('show');
				}
			});
		}

 		/***************
	    ****************
		Submit Form to save organization
		****************
	    ***************/

	    $('#edit-organization-form').submit(function(e){
	  		e.preventDefault();
	  		updateOrganization();
	  	});

	  	function updateOrganization(){
	  		//check to see if there is a resource to edit. If not it creates a new resource.
	  		if ( thisOrganization ){
	        	var organization = thisOrganization;
	        }
	        else
	        {
	        	var organization = new Organization();
	        }
	  		var 
	        	name =  $("#organizationName").val()
	      	;

		    //sets values for resource
		    organization.set("name", name);

      		if (name==""){
		        $('#modal-fail').modal('show');
		        $('#modal-fail .modal-body').html('<p>Nope, You need to at least fill in the <strong>Title</strong>.</p><p> One more time, Slick.</p>');
		     } else{
			    organization.save(null, {
			        success: function(entityId) {
			          	$('#modal-success').modal('show');
			        },
			        error: function(object, error) {
			          //console.log(presenterIndex);
			          console.log(error);
			          $('#modal-fail').modal('show');
			          $('#modal-fail .modal-body').append('<p>And error occured, dumbass. See below.</p><p>' + error + '</p>');
			        }
				});
	      	}
	  	}

	});
</script>

<?php require 'footer.php'; ?>
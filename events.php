<?php 
  $PAGE_TITLE = "Events";
  require 'header.php'; 
?>

<form id="edit-event-form" class="my-form">
	<fieldset>
		<div class="row">
			<div class="col-md-12">
				<h2>Manage Events</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<p>Manage events and schedule for your conference. Here you can add events that are NOT presentation to your conference. Just like <strong>Presentations</strong>, adding events will be reflected in the schedule on the app.</p>
			</div>
			<div class="col-md-6">
				<label for="inputInterests">Select A event</label>
			    <div class="form-group">
			        <select class="form-control" id="select-event" style="margin-right:10px;">
			         	<option value="">Chose One!</option>
			        </select><br>
			        <button class="btn btn-primary" type="button" id="editEvent" >Edit Organization</button>
				<button class="btn block btn-medium btn-danger" id="delete-event" data-id="" style="display:none;"> Delete Event</button>
			    </div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Title</label>
					<input type="text" id="eventTitle" class="form-control">
				</div>
				<div class="form-group">
					<label>Start Time</label>
					<input type="text" id="inputStartTime" class="datePickerField form-control">
				</div>
				<div class="form-group">
					<label>End Time</label>
					<input type="text" id="inputEndTime" class="datePickerField form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="inputBio">Event Location Notes</label>
		      		<textarea rows="6" class="form-control" id="inputEventNote"></textarea>
		      	</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<hr>
				<input type="submit" value="Save event" class="btn btn-lg btn-block btn-success" id="add-presentation" style="margin-top:10px;">
			</div>
			<div class="col-md-6">
				<!--  -->
			</div>
		</div>
	</fieldset>
</form>

<div class="row">
	<div class="col-md-12">
		<hr>
		<h3>Events List</h3>
		<p>A list of events that have been added</p>
		<table class="table" id="eventTable">
	        <thead>
	          <tr>
	            <th>Title</th>
	            <th>Notes</th>
	            <th>Start</th>
	            <th>End</th>
	            <th>edit</th>
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
	    var Event = Parse.Object.extend("Event");
	    var eventQuery = new Parse.Query(Event);

	    //arrays
	    var eventsArray = [];

	    var thisEvent;

	     //Date and Time picker
	    $('.datePickerField').datetimepicker({
	  //   	dateFormat: 'yy-mm-ddT',
	  //   	timeFormat: 'HH:mm.z',
	  //   	timezoneList: [ 
			// 	{ value: -300, label: 'Eastern'}, 
			// 	{ value: -360, label: 'Central' }, 
			// 	{ value: -420, label: 'Mountain' }, 
			// 	{ value: -480, label: 'Pacific' } 
			// ]
	    });

	    //grabs events and populates the select
	    eventQuery.ascending("date").find({
			success: function(results) {
				eventsArray = results;
			  	for(var i = 0; i < results.length; i++) {
				    var 
				      	event = results[i],
						id = event.id,
						title = event.get('title'),
						startTime = event.get('date'),
						endTime = event.get('endDate'),
						note = event.get('locationString'),
						table = $('#eventTable tbody'),
						deleteBtn = $('.btn-delete'),
						select = $('#select-event')
				    ;
				    
				    select.append('<option value="' +  id + '">' + title + '</option>');

					table.append('<tr><td>' + title + '</td><td>' + note + '</td><td>' + startTime + '</td><td>' + endTime + '</td><td><button class="btn btn-small btn-danger btn-delete" id="' +  id + '">remove</button></td><td>');

			  	}
			  	deleteBtn.on('click', function(){
			    	var
			    		eventId = $(this).attr('id')
			    	;
			    	deleteevent(eventId);
			    });
			},
			error: function(error) {
			  alert("Error: " + error.code + " " + error.message);
			}
		});

		function deleteevent(eventId){
	  		eventQuery.get(eventId, {
				success: function(eventId) {
					eventId.destroy({});
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
		Populate for to edit event
		****************
	    ***************/

	    $('#editEvent').on('click', editEvent);
	    function editEvent(){
	    	var
			  entityId =  $("#select-event option:selected").val()
			;
			populateForm(entityId);
	    }
	    function populateForm(entityId){
			eventQuery.get(entityId, {
				success: function(entity) {
					var 
						titleInput = entity.get("title"),
			      		locationNotes = entity.get("locationString"),
			      		startTime = entity.get("date"),
			      		endTime = entity.get("endDate")
		      		;
		      		//sets var for resource to edit
					thisEvent = entity;

		      		$("#eventTitle").val(titleInput);
		      		$("#inputStartTime").val(startTime); 
		      		$("#inputEndTime").val(endTime); 
		        	$("#inputEventNote").val(locationNotes);

		      	},
				error: function(object, error) {

					$('#modal-fail').modal('show');
				}
			});
		}

 		/***************
	    ****************
		Submit Form to save event
		****************
	    ***************/

	    $('#edit-event-form').submit(function(e){
	  		e.preventDefault();
	  		updateEvent();
	  	});

	  	function updateEvent(){
	  		//check to see if there is a resource to edit. If not it creates a new resource.
	  		if ( thisEvent ){
	        	var event = thisEvent;
	        }
	        else
	        {
	        	var event = new Event();
	        }
	  		var 
	        	title =  $("#eventTitle").val(),
	        	startDateField =  $("#inputStartTime").val(),
	        	startDate = new Date(startDateField),
	        	endDateField =  $("#inputEndTime").val(),
	        	endDate = new Date(endDateField),
	        	locationNote =  $("#inputEventNote").val()
	      	;

		    //sets values for resource
		    event.set("title", title);
      		event.set("date", startDate);
      		event.set("endDate", endDate);
      		event.set("locationString", locationNote);

      		if (title==""){
		        $('#modal-fail').modal('show');
		        $('#modal-fail .modal-body').html('<p>Nope, You need to at least fill in the <strong>Title</strong>.</p><p> One more time, Slick.</p>');
		     } else{
			    event.save(null, {
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
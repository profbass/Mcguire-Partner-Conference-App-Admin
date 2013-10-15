<?php 
  require 'header.php'; 
  $PAGE_TITLE = "Events";
?>

<form id="edit-event-form" class="my-form">
	<fieldset>
		<div class="row">
			<div class="span12">
				<legend>Manage Events</legend>
			</div>
		</div>
		<div class="row">
			<div class="span6">
				<label for="inputInterests">Select A event</label>
			    <div class="input-append">
			        <select class="input-xlarge" id="select-event" style="margin-right:10px;">
			         	<option value="">Chose One!</option>
			        </select>
			        <button class="btn" type="button" id="editEvent" >Edit</button>
			    </div>
			</div>
			<div class="span6">
				<button class="btn block btn-medium btn-danger" id="delete-event" data-id="" style="margin-top:10px;display:none;"> Delete Event</button>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="span6">
				<label>Title</label>
				<input type="text" id="eventTitle" class="input span12">
				<label>Start Time</label>
				<input type="text" id="inputStartTime" class="datePickerField">
				<label>End Time</label>
				<input type="text" id="inputEndTime" class="datePickerField">
			</div>
			<div class="span6">
				<label for="inputBio">Event Location Notes</label>
	      		<textarea rows="6" style="width:97%;" id="inputEventNote"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="span6">
				<input type="submit" value="Save event" class="btn btn-medium btn-success" id="add-presentation" style="margin-top:10px;">
			</div>
			<div class="span6">
				<!--  -->
			</div>
		</div>
	</fieldset>
</form>

<div class="row">
	<div class="span12">
		<h3>Events</h3>
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
  	Parse.initialize("jAxfiRG2eVYB2dcLju1qphCLCppqRgt3khUdb5AU", "vTyfhvPE6cyMzPr2oGBb8FYFdUhhil9UqTSrkGF0");
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
	    eventQuery.ascending().find({
			success: function(results) {
				eventsArray = results;
			  	for(var i = 0; i < results.length; i++) {
				    var 
				      	event = results[i],
						id = event.id,
						title = event.get('title'),
						startTime = event.get('startTime'),
						endTime = event.get('endTime'),
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
			      		startTime = entity.get("startTime"),
			      		endTime = entity.get("endTime")
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
	        	startTime =  $("#inputStartTime").val(),
	        	endTime =  $("#inputEndTime").val(),
	        	locationNote =  $("#inputEventNote").val()
	      	;

		    //sets values for resource
		    event.set("title", title);
      		event.set("startTime", startTime);
      		event.set("endTime", endTime);
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